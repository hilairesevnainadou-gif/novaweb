<?php
// app/Http/Controllers/Admin/BillingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Mail\PaymentReceiptMail;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
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
            // Générer le PDF
            $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
            $pdfPath = storage_path('app/temp/invoice_' . $invoice->invoice_number . '.pdf');

            if (!is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            $pdf->save($pdfPath);

            // Envoyer l'email
            Mail::to($invoice->client->email)->send(new InvoiceMail($invoice, $pdfPath));

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
            $pdf = Pdf::loadView('pdf.payment_receipt', compact('payment'));
            $pdfPath = storage_path('app/temp/receipt_' . $payment->payment_number . '.pdf');

            if (!is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            $pdf->save($pdfPath);

            Mail::to($payment->client->email)->send(new PaymentReceiptMail($payment, $pdfPath));

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

        return response()->json([
            'success' => true,
            'count' => $overdueInvoices
        ]);
    }
}
