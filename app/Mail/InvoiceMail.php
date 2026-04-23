<?php
// app/Mail/InvoiceMail.php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\CompanyInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public $pdfPath;
    public $company;

    public function __construct(Invoice $invoice, $pdfPath)
    {
        $this->invoice = $invoice;
        $this->pdfPath = $pdfPath;
        $this->company = CompanyInfo::first();

        // Ajout de l'URL absolue du logo
        if ($this->company && $this->company->logo) {
            $this->company->logo_url = url('storage/' . $this->company->logo);
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Facture N° ' . $this->invoice->invoice_number . ' - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'client' => $this->invoice->client,
                'company' => $this->company
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('facture_' . $this->invoice->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
