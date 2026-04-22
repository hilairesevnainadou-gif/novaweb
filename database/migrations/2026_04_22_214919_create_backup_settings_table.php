<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_backup_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->string('backup_email')->nullable();
            $table->string('backup_frequency')->default('daily');
            $table->string('backup_time')->default('02:00');
            $table->integer('auto_clean_days')->default(30);
            $table->string('backup_type')->default('full');
            $table->string('backup_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_backup_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_settings');
    }
};
