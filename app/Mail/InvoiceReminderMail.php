<?php
// app/Mail/InvoiceReminderMail.php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\CompanyInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $type;
    public $company;

    public function __construct(Invoice $invoice, $type = 'upcoming', $company = null)
    {
        $this->invoice = $invoice;
        $this->type = $type;

        // Récupération des infos entreprise si non fournies
        if ($company) {
            $this->company = $company;
        } else {
            $this->company = CompanyInfo::first();
        }

        // Ajout de l'URL absolue du logo
        if ($this->company && $this->company->logo) {
            $this->company->logo_url = url('storage/' . $this->company->logo);
        }
    }

    public function envelope(): Envelope
    {
        $subject = $this->type === 'overdue'
            ? 'URGENT : Votre facture est en retard de paiement'
            : 'Rappel : Votre facture arrive à échéance';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-reminder',
            with: [
                'invoice' => $this->invoice,
                'type' => $this->type,
                'company' => $this->company,
            ]
        );
    }
}
