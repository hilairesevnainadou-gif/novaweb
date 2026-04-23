<?php
// ╔══════════════════════════════════════════════════════════════════╗
// ║  app/Http/Controllers/Admin/BillingController.php               ║
// ╚══════════════════════════════════════════════════════════════════╝

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
use App\Traits\GeneratesQrCode;          // ← Trait QR Code
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
    use GeneratesQrCode;                 // ← Activer le trait

    // ══════════════════════════════════════════════════════════════
    //  HELPERS INTERNES
    // ══════════════════════════════════════════════════════════════

    /**
     * Envoie une notification à tous les administrateurs.
     */
    private function notifyAdmins(string $type, string $title, string $message, ?string $url = null): void
    {
        $admins = User::whereHas('roles', fn($q) =>
            $q->whereIn('name', ['super-admin', 'admin'])
        )->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'url'     => $url,
                'is_read' => false,
            ]);
        }
    }

    /**
     * Envoie une notification à un utilisateur spécifique.
     */
    private function sendNotification(int $userId, string $type, string $title, string $message, ?string $url = null): bool
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'url'     => $url,
                'is_read' => false,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Prépare le répertoire temporaire pour les PDFs.
     */
    private function ensureTempDir(): string
    {
        $dir = storage_path('app/temp');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Génère le PDF d'une facture avec QR code intégré.
     */
  private function buildInvoicePdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
{
    $company = \App\Models\CompanyInfo::first();

    // Générer le QR code
    $qrSvg = $this->generateQrCode(
        route('admin.billing.invoices.show', $invoice),
        80
    );

    return Pdf::loadView('pdf.invoice', compact('invoice', 'company', 'qrSvg'))
              ->setPaper('a4', 'portrait');
}

private function buildReceiptPdf(Payment $payment): \Barryvdh\DomPDF\PDF
{
    $company = \App\Models\CompanyInfo::first();

    // Générer le QR code
    $qrSvg = $this->generateQrCode(
        route('admin.billing.payments.show', $payment),
        80
    );

    return Pdf::loadView('pdf.payment_receipt', compact('payment', 'company', 'qrSvg'))
              ->setPaper('a4', 'portrait');
}



    /**
     * Génère un numéro de paiement unique.
     */
    private function generatePaymentNumber(): string
    {
        $year = date('Y');
        $last = Payment::whereYear('created_at', $year)->count();
        return 'PAY-' . $year . '-' . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }

    // ══════════════════════════════════════════════════════════════
    //  FACTURES
    // ══════════════════════════════════════════════════════════════

    /**
     * Liste des factures.
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        $totalInvoiced    = Invoice::sum('total');
        $totalPaid        = Invoice::sum('paid_amount');
        $totalOutstanding = Invoice::whereIn('status', ['sent', 'pending', 'partially_paid'])->sum('remaining_amount');
        $overdueCount     = Invoice::where('due_date', '<', Carbon::now())
                                   ->whereIn('status', ['sent', 'pending', 'partially_paid'])
                                   ->count();
        $clients          = Client::active()->ordered()->get();

        return view('admin.billing.invoices', compact(
            'invoices', 'totalInvoiced', 'totalPaid', 'totalOutstanding', 'overdueCount', 'clients'
        ));
    }

    /**
     * Formulaire de création de facture.
     */
    public function createInvoiceForm(Request $request)
    {
        $clients          = Client::active()->ordered()->get();
        $services         = Service::active()->ordered()->get();
        $selectedClientId = $request->client_id;

        return view('admin.billing.create-invoice', compact('clients', 'services', 'selectedClientId'));
    }

    /**
     * Créer une facture.
     */
    public function createInvoice(Request $request)
    {
        $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'service_id'   => 'nullable|exists:services,id',
            'service_name' => 'required_without:service_id|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'due_days'     => 'nullable|integer|min:1|max:365',
            'tax_rate'     => 'nullable|numeric|min:0|max:100',
        ]);

        $client      = Client::findOrFail($request->client_id);
        $serviceId   = $request->service_id;
        $serviceName = $request->service_name;

        if ($serviceId && $service = Service::find($serviceId)) {
            $serviceName = $service->title;
        }

        $subtotal  = (float) $request->amount;
        $taxRate   = (float) ($request->tax_rate ?? 18);
        $taxAmount = $subtotal * $taxRate / 100;
        $total     = $subtotal + $taxAmount;
        $dueDate   = Carbon::now()->addDays((int) ($request->due_days ?? 30));

        $clientServiceId = null;
        if ($serviceId) {
            $clientService   = ClientService::firstOrCreate(
                ['client_id' => $client->id, 'service_id' => $serviceId],
                ['service_name' => $serviceName, 'price' => $total, 'start_date' => Carbon::now(), 'status' => 'active']
            );
            $clientServiceId = $clientService->id;
        }

        $invoice = Invoice::create([
            'client_id'         => $client->id,
            'service_id'        => $serviceId,
            'client_service_id' => $clientServiceId,
            'subtotal'          => $subtotal,
            'tax_rate'          => $taxRate,
            'tax_amount'        => $taxAmount,
            'discount'          => 0,
            'total'             => $total,
            'paid_amount'       => 0,
            'remaining_amount'  => $total,
            'issue_date'        => Carbon::now(),
            'due_date'          => $dueDate,
            'status'            => 'draft',
            'type'              => 'invoice',
            'description'       => $request->description ?? $serviceName,
        ]);

        $this->notifyAdmins(
            'billing',
            'Nouvelle facture créée',
            "Facture #{$invoice->invoice_number} créée pour {$client->name} — " . number_format($total, 0, ',', ' ') . ' FCFA',
            route('admin.billing.invoices.show', $invoice)
        );

        return redirect()->route('admin.billing.invoices.show', $invoice)
                         ->with('success', 'Facture créée avec succès');
    }

    /**
     * Afficher le détail d'une facture.
     */
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['client', 'service', 'payments']);
        $clients  = Client::active()->ordered()->get();
        $services = Service::active()->ordered()->get();

        Log::info('Facture consultée : ' . $invoice->invoice_number . ' par ' . auth()->user()->email);

        return view('admin.billing.show-invoice', compact('invoice', 'clients', 'services'));
    }

    /**
     * Télécharger la facture en PDF (avec QR code).
     */
    public function downloadInvoicePdf(Invoice $invoice)
    {
        return $this->buildInvoicePdf($invoice)
                    ->download('facture_' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Envoyer une facture par email (PDF avec QR code en pièce jointe).
     */
    public function sendInvoice(Invoice $invoice)
    {
        if (! $invoice->client->email) {
            return back()->with('error', "Le client n'a pas d'adresse email.");
        }

        try {
            $tmpDir  = $this->ensureTempDir();
            $pdfPath = $tmpDir . '/invoice_' . $invoice->invoice_number . '.pdf';

            $this->buildInvoicePdf($invoice)->save($pdfPath);

            Mail::to($invoice->client->email)
                ->send(new InvoiceMail($invoice, $pdfPath));

            if ($invoice->status === 'draft') {
                $invoice->update(['status' => 'sent']);
            }
            $invoice->update(['email_sent_at' => Carbon::now()]);

            @unlink($pdfPath);

            return back()->with('success', 'Facture envoyée par email avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur envoi facture : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un rappel de paiement.
     */
    public function sendReminder(Invoice $invoice)
    {
        if (! $invoice->client->email) {
            return back()->with('error', "Le client n'a pas d'adresse email.");
        }

        try {
            $company = \App\Models\CompanyInfo::first();
            Mail::to($invoice->client->email)
                ->send(new InvoiceReminderMail($invoice, 'manual', $company));

            return back()->with('success', 'Rappel de paiement envoyé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur envoi rappel : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi du rappel.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  PAIEMENTS
    // ══════════════════════════════════════════════════════════════

    /**
     * Liste des paiements.
     */
    public function payments(Request $request)
    {
        $query = Payment::with('client', 'invoice');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);
        $clients  = Client::active()->ordered()->get();

        return view('admin.billing.payments', compact('payments', 'clients'));
    }

    /**
     * Afficher le détail d'un paiement.
     */
    public function showPayment(Payment $payment)
    {
        $payment->load(['client', 'invoice']);
        Log::info('Paiement consulté : ' . $payment->payment_number . ' par ' . auth()->user()->email);

        return view('admin.billing.show-payment', compact('payment'));
    }

    /**
     * Enregistrer un paiement sur une facture.
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1|max:' . $invoice->remaining_amount,
            'payment_type'   => 'required|in:deposit,partial,full',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money,card',
            'payment_date'   => 'required|date',
            'reference'      => 'nullable|string',
            'notes'          => 'nullable|string',
        ]);

        $amount        = (float) $request->amount;
        $newPaidAmount = $invoice->paid_amount + $amount;
        $newRemaining  = $invoice->remaining_amount - $amount;

        $invoiceStatus = $newRemaining <= 0 ? 'paid'
                       : ($newPaidAmount > 0  ? 'partially_paid' : $invoice->status);
        $paidDate      = $newRemaining <= 0 ? Carbon::now() : null;

        $payment = Payment::create([
            'payment_number'    => $this->generatePaymentNumber(),
            'client_id'         => $invoice->client_id,
            'invoice_id'        => $invoice->id,
            'client_service_id' => $invoice->client_service_id,
            'amount'            => $amount,
            'remaining_balance' => $newRemaining,
            'payment_type'      => $request->payment_type,
            'payment_method'    => $request->payment_method,
            'reference'         => $request->reference,
            'payment_date'      => Carbon::parse($request->payment_date),
            'status'            => 'completed',
            'notes'             => $request->notes,
        ]);

        $invoice->update([
            'paid_amount'      => $newPaidAmount,
            'remaining_amount' => $newRemaining,
            'status'           => $invoiceStatus,
            'paid_date'        => $paidDate,
        ]);

        // Envoyer le reçu automatiquement si le client l'a activé
        if ($invoice->client->invoice_by_email && $invoice->client->email) {
            $this->sendPaymentReceipt($payment);
        }

        // Notifications
        $this->notifyAdmins(
            'billing_payment',
            'Nouveau paiement enregistré',
            "Paiement de " . number_format($amount, 0, ',', ' ') . " FCFA sur la facture #{$invoice->invoice_number}",
            route('admin.billing.payments.show', $payment)
        );

        if ($invoiceStatus === 'paid') {
            $this->notifyAdmins(
                'billing_paid',
                'Facture entièrement payée',
                "Facture #{$invoice->invoice_number} — {$invoice->client->name} — " . number_format($invoice->total, 0, ',', ' ') . ' FCFA',
                route('admin.billing.invoices.show', $invoice)
            );
        }

        if ($request->payment_type === 'deposit') {
            $this->notifyAdmins(
                'billing_deposit',
                'Acompte enregistré',
                "Acompte de " . number_format($amount, 0, ',', ' ') . " FCFA sur #{$invoice->invoice_number}. Solde : " . number_format($newRemaining, 0, ',', ' ') . ' FCFA',
                route('admin.billing.invoices.show', $invoice)
            );
        }

        $msg = $request->payment_type === 'deposit'
             ? 'Acompte enregistré avec succès.'
             : 'Paiement enregistré avec succès.';

        return redirect()->route('admin.billing.invoices.show', $invoice)
                         ->with('success', $msg);
    }

    /**
     * Envoyer (ou renvoyer) le reçu de paiement par email (PDF avec QR code).
     */
    public function sendPaymentReceipt(Payment $payment)
    {
        if (! $payment->client->email) return;

        try {
            $tmpDir  = $this->ensureTempDir();
            $pdfPath = $tmpDir . '/receipt_' . $payment->payment_number . '.pdf';

            $this->buildReceiptPdf($payment)->save($pdfPath);

            Mail::to($payment->client->email)
                ->send(new PaymentReceiptMail($payment, $pdfPath));

            $payment->update(['email_sent_at' => Carbon::now()]);

            @unlink($pdfPath);
        } catch (\Exception $e) {
            Log::error('Erreur envoi reçu : ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le reçu de paiement en PDF (avec QR code).
     */
    public function downloadReceiptPdf(Payment $payment)
    {
        return $this->buildReceiptPdf($payment)
                    ->download('recu_' . $payment->payment_number . '.pdf');
    }

    /**
     * Renvoyer un reçu de paiement par email.
     */
    public function resendReceipt(Payment $payment)
    {
        if (! $payment->client->email) {
            return back()->with('error', "Le client n'a pas d'adresse email.");
        }

        try {
            $this->sendPaymentReceipt($payment);

            $this->notifyAdmins(
                'billing_receipt',
                'Reçu de paiement renvoyé',
                "Reçu #{$payment->payment_number} renvoyé à {$payment->client->name}",
                route('admin.billing.payments.show', $payment)
            );

            return back()->with('success', 'Reçu renvoyé par email avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur renvoi reçu : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  TÂCHES PLANIFIÉES (Scheduler / Artisan)
    // ══════════════════════════════════════════════════════════════

    /**
     * Vérifier et envoyer des rappels pour les factures.
     */
    public function checkAndSendReminders()
    {
        $company = \App\Models\CompanyInfo::first();

        // Factures arrivant à échéance dans 3 jours
        $upcoming = Invoice::where('due_date', '=', Carbon::now()->addDays(3)->toDateString())
                           ->whereIn('status', ['sent', 'pending', 'partially_paid'])
                           ->get();

        foreach ($upcoming as $invoice) {
            if ($invoice->client?->email) {
                try {
                    Mail::to($invoice->client->email)
                        ->send(new InvoiceReminderMail($invoice, 'upcoming', $company));

                    Log::info('Rappel échéance envoyé : ' . $invoice->invoice_number);

                    $this->notifyAdmins(
                        'billing_reminder',
                        "Rappel d'échéance envoyé",
                        "Rappel envoyé à {$invoice->client->name} pour la facture {$invoice->invoice_number}",
                        route('admin.billing.invoices.show', $invoice)
                    );
                } catch (\Exception $e) {
                    Log::error('Erreur rappel : ' . $e->getMessage());
                }
            }
        }

        // Factures en retard
        $overdue = Invoice::where('due_date', '<', Carbon::now())
                          ->whereIn('status', ['sent', 'pending', 'partially_paid'])
                          ->get();

        foreach ($overdue as $invoice) {
            if ($invoice->client?->email) {
                try {
                    Mail::to($invoice->client->email)
                        ->send(new InvoiceReminderMail($invoice, 'overdue', $company));

                    $invoice->update(['status' => 'overdue']);

                    Log::info('Rappel retard envoyé : ' . $invoice->invoice_number);

                    $this->notifyAdmins(
                        'billing_overdue',
                        'Facture en retard',
                        "Facture {$invoice->invoice_number} — {$invoice->client->name} est en retard",
                        route('admin.billing.invoices.show', $invoice)
                    );
                } catch (\Exception $e) {
                    Log::error('Erreur rappel retard : ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success'        => true,
            'upcoming_count' => $upcoming->count(),
            'overdue_count'  => $overdue->count(),
        ]);
    }

    /**
     * Mettre à jour le statut des factures en retard.
     */
    public function updateOverdueStatus()
    {
        $count = Invoice::where('due_date', '<', Carbon::now())
                        ->whereIn('status', ['sent', 'pending', 'partially_paid'])
                        ->update(['status' => 'overdue']);

        if ($count > 0) {
            $this->notifyAdmins(
                'billing_overdue',
                'Factures en retard',
                "{$count} facture(s) passée(s) en statut « En retard »",
                route('admin.billing.invoices.index')
            );
        }

        return response()->json(['success' => true, 'count' => $count]);
    }
}
