<?php
// database/migrations/2026_01_20_000002_create_client_service_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Informations spécifiques au service pour ce client
            $table->string('service_name')->nullable();           // Nom personnalisé du service
            $table->decimal('price', 12, 2)->nullable();          // Prix personnalisé
            $table->date('start_date')->nullable();               // Date de début
            $table->date('end_date')->nullable();                 // Date de fin (si abonnement)
            $table->string('status')->default('active');          // active, pending, completed, cancelled
            $table->text('notes')->nullable();                    // Notes spécifiques
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_service');
    }
};