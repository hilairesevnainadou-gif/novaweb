<?php
// database/migrations/2026_01_20_000003_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();          // Numéro de facture unique
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_service_id')->nullable()->constrained('client_service')->onDelete('set null');
            
            // Montants
            $table->decimal('subtotal', 12, 2);                  // Sous-total
            $table->decimal('tax_rate', 5, 2)->default(18);      // Taux de TVA (%)
            $table->decimal('tax_amount', 12, 2)->default(0);    // Montant TVA
            $table->decimal('discount', 12, 2)->default(0);      // Réduction
            $table->decimal('total', 12, 2);                     // Total
            $table->decimal('paid_amount', 12, 2)->default(0);   // Montant payé
            $table->decimal('remaining_amount', 12, 2);          // Reste à payer
            
            // Dates
            $table->date('issue_date');                          // Date d'émission
            $table->date('due_date');                            // Date d'échéance
            $table->date('paid_date')->nullable();               // Date de paiement complet
            
            // Statut
            $table->enum('status', [
                'draft',           // Brouillon
                'sent',            // Envoyée
                'pending',         // En attente
                'partially_paid',  // Partiellement payée
                'paid',            // Payée
                'overdue',         // En retard
                'cancelled'        // Annulée
            ])->default('draft');
            
            // Type de facture
            $table->enum('type', ['invoice', 'quote', 'credit_note'])->default('invoice');
            
            // Description
            $table->text('description')->nullable();             // Description de la prestation
            $table->text('notes')->nullable();                   // Notes supplémentaires
            $table->text('terms')->nullable();                   // Conditions de paiement
            
            // Informations bancaires (à la volée)
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_iban')->nullable();
            
            // Envoi email
            $table->timestamp('email_sent_at')->nullable();      // Date d'envoi de l'email
            $table->integer('email_retries')->default(0);        // Tentatives d'envoi
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};