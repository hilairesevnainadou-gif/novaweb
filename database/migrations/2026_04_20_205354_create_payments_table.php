<?php
// database/migrations/2026_01_20_000004_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();          // Numéro de paiement
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_service_id')->nullable()->constrained('client_service')->onDelete('set null');

            // Montants
            $table->decimal('amount', 12, 2);                    // Montant payé
            $table->decimal('remaining_balance', 12, 2);         // Solde restant après ce paiement

            // Type de paiement
            $table->enum('payment_type', [
                'deposit',      // Acompte
                'partial',      // Paiement partiel
                'full',         // Paiement complet
                'installment'   // Versement
            ])->default('full');

            // Mode de paiement
            $table->enum('payment_method', [
                'cash',          // Espèces
                'bank_transfer', // Virement bancaire
                'check',         // Chèque
                'mobile_money',  // Mobile Money (MTN, Moov)
                'card',          // Carte bancaire
                'other'          // Autre
            ])->default('bank_transfer');

            // Références
            $table->string('reference')->nullable();             // Référence du paiement (transaction ID)
            $table->string('receipt_number')->nullable();        // Numéro de reçu

            // Dates
            $table->date('payment_date');                        // Date du paiement

            // Statut
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');

            // Informations supplémentaires
            $table->text('notes')->nullable();                   // Notes sur le paiement
            $table->text('bank_details')->nullable();            // Détails bancaires pour virement

            // Preuve de paiement
            $table->string('proof_file')->nullable();            // Fichier justificatif

            // Envoi email
            $table->timestamp('email_sent_at')->nullable();      // Date d'envoi du reçu

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
