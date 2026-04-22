<?php
// database/migrations/2024_01_01_000001_create_maintenance_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des appareils
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('category')->default('other');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->string('status')->default('operational');
            $table->string('location')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->json('technical_specs')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'is_active']);
            $table->index('client_id');
        });

        // Table des interventions
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->string('intervention_number')->unique();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('problem_type')->nullable();
            $table->text('problem_description')->nullable();
            $table->text('solution')->nullable();
            $table->string('status')->default('pending');
            $table->string('priority')->default('medium');
            $table->integer('evolution_level')->default(1);
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->decimal('actual_cost', 10, 2)->default(0);
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('scheduled_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->json('parts_used')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->string('signature')->nullable();
            $table->boolean('client_rated')->default(false);
            $table->integer('client_rating')->nullable();
            $table->text('client_feedback')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('technician_id');
            $table->index('device_id');
            $table->index('client_id');
            $table->index('scheduled_date');
        });

        // Table des évolutions d'intervention
        Schema::create('intervention_evolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('previous_level');
            $table->integer('new_level');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('additional_cost', 10, 2)->default(0);
            $table->integer('time_spent_minutes')->nullable();
            $table->timestamps();

            $table->index('intervention_id');
            $table->index('user_id');
        });

        // Table des dépenses d'intervention
        Schema::create('intervention_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2);
            $table->string('reference')->nullable();
            $table->string('invoice_file')->nullable();
            $table->timestamps();

            $table->index('intervention_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('intervention_expenses');
        Schema::dropIfExists('intervention_evolutions');
        Schema::dropIfExists('interventions');
        Schema::dropIfExists('devices');
    }
};
