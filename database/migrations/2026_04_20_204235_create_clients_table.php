<?php
// database/migrations/xxxx_xx_xx_create_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Type de client
            $table->enum('client_type', ['company', 'individual'])->default('company');

            // Champs entreprise (uniquement pour company)
            $table->string('company_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('website')->nullable();

            // Champs communs
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bénin');
            $table->string('logo')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_position')->nullable();

            // Facturation
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly', 'one_time'])->default('monthly');
            $table->boolean('invoice_by_email')->default(true);

            // Statut
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Index
            $table->index('client_type');
            $table->index('is_active');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
