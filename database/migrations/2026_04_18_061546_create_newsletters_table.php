<?php
// database/migrations/[timestamp]_create_newsletters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('unsubscribe_token', 64)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('is_active');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('newsletters');
    }
};
