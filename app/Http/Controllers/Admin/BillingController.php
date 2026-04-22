<?php
// app/Http/Controllers/Admin/BillingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\InvoiceReminderMail;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
    /**
     * Envoie une notification aux administrateurs
     */
    private function notifyAdmins($type, $title, $message, $url = null)
    {
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['super-admin', 'admin']);
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false
            ]);
        }
    }

    /**
     * Envoie une notification à un utilisateur spécifique
     */
    private function sendNotification($userId, $type, $title, $message, $url = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier et envoyer des rappels pour les factures
     */
    public function checkAndSendReminders()
    {
        // Factures arrivant à échéance dans 3 jours
        $upcomingInvoices = Invoice::where('due_date', '=', Carbon::now()->addDays(3)->toDateString())
            ->whereIn('status', ['sent', 'pending', 'partially_paid'])
            ->get();

        foreach ($upcomingInvoices as $invoice) {
            if ($invoice->client && $invoice->client->email) {
                try {
                    // Envoyer un rappel au client
                    Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice, 'upcoming'));

                    // Enregistrer dans les logs
                    Log::info('Rappel d\'échéance envoyé pour la facture ' . $invoice->invoice_number);

                    // Notifier les admins
                    $this->notifyAdmins(
                        'billing_reminder',
                        'Rappel d\'échéance envoyé',
                        "Un rappel d'échéance a été envoyé au client {$invoice->client->name} pour la facture {$invoice->invoice_number}",
                        route('admin.billing.invoices.show', $invoice)
                    );
                } catch (\Exception $e) {
                    Log::error('Erreur envoi rappel: ' . $e->getMessage());
                }
            }
        }

        // Factures en retard (due_date dépassée)
        $overdueInvoices = Invoice::where('due_date', '<', Carbon::now())
            ->whereIn('status', ['sent', 'pending', 'partially_paid'])
            ->get();

        foreach ($overdueInvoices as $invoice) {
            if ($invoice->client && $invoice->client->email) {
                try {
                    // Envoyer un rappel de retard au client
                    Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice, 'overdue'));

                    // Mettre à jour le statut
                    $invoice->update(['status' => 'overdue']);

                    // Enregistrer dans les logs
                    Log::info('Rappel de retard envoyé pour la facture ' . $invoice->invoice_number);

                    // Notifier les admins
                    $this->notifyAdmins(
                        'billing_overdue',
                        'Facture en retard',
                        "La facture {$invoice->invoice_number} du client {$invoice->client->name} est en retard de paiement",
                        route('admin.billing.invoices.show', $invoice)
                    );
                } catch (\Exception $e) {
                    Log::error('Erreur envoi rappel retard: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'upcoming_count' => $upcomingInvoices->count(),
            'overdue_count' => $overdueInvoices->count()
        ]);
    }

    /**
     * Liste des factures
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with('client');

        // Filtres
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $totalInvoiced = Invoice::sum('total');
        $totalPaid = Invoice::sum('paid_amount');
        $totalOutstanding = Invoice::whereIn('status', ['sent', 'pending', 'partially_paid'])->sum('remaining_amount');
        $overdueCount = Invoice::where('due_date', '<', Carbon::now())
            ->whereIn('status', ['sent', 'pending', 'partially_paid'])
            ->count();

        $clients = Client::active()->ordered()->get();

        return view('admin.billing.invoices', compact('invoices', 'totalInvoiced', 'totalPaid', 'totalOutstanding', 'overdueCount', 'clients'));
    }

    /**
     * Afficher le formulaire de création de facture
     */
    public function createInvoiceForm(Request $request)
    {
        $clients = Client::active()->ordered()->get();
        $services = Service::active()->ordered()->get();
        $selectedClientId = $request->client_id;

        return view('admin.billing.create-invoice', compact('clients', 'services', 'selectedClientId'));
    }

    /**
     * Créer une facture
     */
    public function createInvoice(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'nullable|exists:services,id',
            'service_name' => 'required_without:service_id|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'due_days' => 'nullable|integer|min:1|max:365',
            'tax_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $client = Client::findOrFail($request->client_id);

        // Récupérer le nom du service
        $serviceName = $request->service_name;
        $serviceId = $request->service_id;

        if ($serviceId) {
            $service = Service::find($serviceId);
            if ($service) {
                $serviceName = $service->title;
            }
        }

        // Calcul des montants
        $subtotal = (float) $request->amount;
        $taxRate = (float) ($request->tax_rate ?? 18);
        $taxAmount = $subtotal * $taxRate / 100;
        $total = $subtotal + $taxAmount;

        // Calcul de la date d'échéance
        $dueDays = (int) ($request->due_days ?? 30);
        $dueDate = Carbon::now()->addDays($dueDays);

        // Création de la liaison client-service si un service est sélectionné
        $clientServiceId = null;
        if ($serviceId) {
            $clientService = ClientService::firstOrCreate([
                'client_id' => $client->id,
                'service_id' => $serviceId,
            ], [
                'service_name' => $serviceName,
                'price' => $total,
                'start_date' => Carbon::now(),
                'status' => 'active'
            ]);
            $clientServiceId = $clientService->id;
        }

        // Création de la facture
        $invoice = Invoice::create([
            'client_id' => $client->id,
            'service_id' => $serviceId,
            'client_service_id' => $clientServiceId,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'total' => $total,
            'paid_amount' => 0,
            'remaining_amount' => $total,
            'issue_date' => Carbon::now(),
            'due_date' => $dueDate,
            'status' => 'draft',
            'type' => 'invoice',
            'description' => $request->description ?? $serviceName
        ]);

        // --- NOTIFICATION : Nouvelle facture créée ---
        $this->notifyAdmins(
            'billing',
            'Nouvelle facture créée',
            "Une nouvelle facture #{$invoice->invoice_number} a été créée pour le client {$client->name} d'un montant de " . number_format($total, 0, ',', ' ') . " FCFA",
            route('admin.billing.invoices.show', $invoice)
        );

        return redirect()->route('admin.billing.invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès');
    }

    /**
     * Afficher le détail d'une facture
     */
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['client', 'service', 'payments']);
        $clients = Client::active()->ordered()->get();
        $services = Service::active()->ordered()->get();

        // --- NOTIFICATION : Consultation de la facture (optionnel) ---
        // Notification silencieuse pour traçabilité
        Log::info('Facture consultée: ' . $invoice->invoice_number . ' par ' . auth()->user()->email);

        return view('admin.billing.show-invoice', compact('invoice', 'clients', 'services'));
    }

    /**
 * Envoyer une facture par email
 */
public function sendInvoice(Invoice $invoice)
{
    if (!$invoice->client->email) {
        return back()->with('error', 'Le client n\'a pas d\'adresse email');
    }

    try {
        // Récupérer les informations de l'entreprise
        $company = \App\Models\CompanyInfo::first();

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'company'));
        $pdfPath = storage_path('app/temp/invoice_' . $invoice->invoice_number . '.pdf');

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        $pdf->save($pdfPath);

        // Envoyer l'email
        Mail::to($invoice->client->email)->send(new InvoiceMail($invoice, $pdfPath, $company));

        // Mettre à jour le statut
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }
        $invoice->update(['email_sent_at' => Carbon::now()]);

        // Supprimer le fichier temporaire
        unlink($pdfPath);

        return back()->with('success', 'Facture envoyée par email avec succès');
    } catch (\Exception $e) {
        Log::error('Erreur envoi facture: ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
    }
}


   /**
 * Envoyer un rappel de paiement
 */
public function sendReminder(Invoice $invoice)
{
    if (!$invoice->client->email) {
        return back()->with('error', 'Le client n\'a pas d\'adresse email');
    }

    try {
        $company = \App\Models\CompanyInfo::first();

        Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice, 'manual', $company));

        return back()->with('success', 'Rappel de paiement envoyé avec succès');
    } catch (\Exception $e) {
        Log::error('Erreur envoi rappel: ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de l\'envoi du rappel');
    }
}

    /**
     * Enregistrer un paiement (acompte)
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->remaining_amount,
            'payment_type' => 'required|in:deposit,partial,full',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money,card',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $amount = (float) $request->amount;
        $newPaidAmount = $invoice->paid_amount + $amount;
        $newRemaining = $invoice->remaining_amount - $amount;

        // Déterminer le nouveau statut de la facture
        $oldStatus = $invoice->status;
        if ($newRemaining <= 0) {
            $invoiceStatus = 'paid';
            $paidDate = Carbon::now();
        } elseif ($newPaidAmount > 0 && $newRemaining > 0) {
            $invoiceStatus = 'partially_paid';
            $paidDate = null;
        } else {
            $invoiceStatus = $invoice->status;
            $paidDate = null;
        }

        // Créer le paiement
        $payment = Payment::create([
            'payment_number' => $this->generatePaymentNumber(),
            'client_id' => $invoice->client_id,
            'invoice_id' => $invoice->id,
            'client_service_id' => $invoice->client_service_id,
            'amount' => $amount,
            'remaining_balance' => $newRemaining,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
            'payment_date' => Carbon::parse($request->payment_date),
            'status' => 'completed',
            'notes' => $request->notes
        ]);

        // Mettre à jour la facture
        $invoice->update([
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $newRemaining,
            'status' => $invoiceStatus,
            'paid_date' => $paidDate
        ]);

        // Envoyer le reçu par email
        if ($invoice->client->invoice_by_email && $invoice->client->email) {
            $this->sendPaymentReceipt($payment);
        }

        // --- NOTIFICATIONS ---

        // Notification pour paiement effectué
        $paymentMessage = "Un paiement de " . number_format($amount, 0, ',', ' ') . " FCFA a été enregistré pour la facture #{$invoice->invoice_number}";

        $this->notifyAdmins(
            'billing_payment',
            'Nouveau paiement enregistré',
            $paymentMessage,
            route('admin.billing.payments.show', $payment)
        );

        // Si la facture est complètement payée
        if ($invoiceStatus === 'paid') {
            $this->notifyAdmins(
                'billing_paid',
                'Facture entièrement payée',
                "La facture #{$invoice->invoice_number} du client {$invoice->client->name} a été entièrement payée. Montant total: " . number_format($invoice->total, 0, ',', ' ') . " FCFA",
                route('admin.billing.invoices.show', $invoice)
            );
        }

        // Si c'est un acompte
        if ($request->payment_type === 'deposit') {
            $this->notifyAdmins(
                'billing_deposit',
                'Acompte enregistré',
                "Un acompte de " . number_format($amount, 0, ',', ' ') . " FCFA a été enregistré pour la facture #{$invoice->invoice_number}. Solde restant: " . number_format($newRemaining, 0, ',', ' ') . " FCFA",
                route('admin.billing.invoices.show', $invoice)
            );
        }

        $message = $request->payment_type === 'deposit'
            ? 'Acompte enregistré avec succès'
            : 'Paiement enregistré avec succès';

        return redirect()->route('admin.billing.invoices.show', $invoice)
            ->with('success', $message);
    }

    /**
 * Envoyer un reçu de paiement
 */
public function sendPaymentReceipt(Payment $payment)
{
    if (!$payment->client->email) return;

    try {
        $company = \App\Models\CompanyInfo::first();

        $pdf = Pdf::loadView('pdf.payment_receipt', compact('payment', 'company'));
        $pdfPath = storage_path('app/temp/receipt_' . $payment->payment_number . '.pdf');

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        $pdf->save($pdfPath);

        Mail::to($payment->client->email)->send(new PaymentReceiptMail($payment, $pdfPath, $company));

        $payment->update(['email_sent_at' => Carbon::now()]);
        unlink($pdfPath);
    } catch (\Exception $e) {
        Log::error('Erreur envoi reçu: ' . $e->getMessage());
    }
}

    /**
     * Liste des paiements
     */
    public function payments(Request $request)
    {
        $query = Payment::with('client', 'invoice');

        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);
        $clients = Client::active()->ordered()->get();

        return view('admin.billing.payments', compact('payments', 'clients'));
    }

    /**
     * Afficher le détail d'un paiement
     */
    public function showPayment(Payment $payment)
    {
        $payment->load(['client', 'invoice']);

        // --- NOTIFICATION : Consultation du paiement ---
        Log::info('Paiement consulté: ' . $payment->payment_number . ' par ' . auth()->user()->email);

        return view('admin.billing.show-payment', compact('payment'));
    }

    /**
     * Renvoyer un reçu de paiement
     */
    public function resendReceipt(Payment $payment)
    {
        if (!$payment->client->email) {
            return back()->with('error', 'Le client n\'a pas d\'adresse email');
        }

        try {
            $this->sendPaymentReceipt($payment);

            // --- NOTIFICATION : Reçu renvoyé ---
            $this->notifyAdmins(
                'billing_receipt',
                'Reçu de paiement renvoyé',
                "Le reçu pour le paiement #{$payment->payment_number} a été renvoyé au client {$payment->client->name}",
                route('admin.billing.payments.show', $payment)
            );

            return back()->with('success', 'Reçu renvoyé par email avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur renvoi reçu: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    /**
     * Générer un numéro de paiement unique
     */
    private function generatePaymentNumber()
    {
        $year = date('Y');
        $lastPayment = Payment::whereYear('created_at', $year)->count();
        return 'PAY-' . $year . '-' . str_pad($lastPayment + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Mettre à jour le statut des factures en retard
     */
    public function updateOverdueStatus()
    {
        $overdueInvoices = Invoice::where('due_date', '<', Carbon::now())
            ->whereIn('status', ['sent', 'pending', 'partially_paid'])
            ->update(['status' => 'overdue']);

        // --- NOTIFICATION : Factures en retard ---
        if ($overdueInvoices > 0) {
            $this->notifyAdmins(
                'billing_overdue',
                'Factures en retard',
                "{$overdueInvoices} facture(s) sont maintenant en retard de paiement",
                route('admin.billing.invoices.index')
            );
        }

        return response()->json([
            'success' => true,
            'count' => $overdueInvoices
        ]);
    }
}
