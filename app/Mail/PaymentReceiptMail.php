<?php
// app/Mail/PaymentReceiptMail.php

namespace App\Mail;

use App\Models\Payment;
use App\Models\CompanyInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public $pdfPath;
    public $company;

    public function __construct(Payment $payment, $pdfPath)
    {
        $this->payment = $payment;
        $this->pdfPath = $pdfPath;
        $this->company = CompanyInfo::first();

        // Ajout de l'URL absolue du logo
        if ($this->company && $this->company->logo) {
            $this->company->logo_url = url('storage/' . $this->company->logo);
        }
    }

    public function envelope(): Envelope
    {
        $type = $this->payment->payment_type === 'deposit' ? 'Acompte' : 'Paiement';
        return new Envelope(
            subject: $type . ' N° ' . $this->payment->payment_number . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_receipt',
            with: [
                'payment' => $this->payment,
                'client' => $this->payment->client,
                'company' => $this->company
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('recu_' . $this->payment->payment_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
