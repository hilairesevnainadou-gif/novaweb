<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('date');
            $table->integer('minutes');
            $table->boolean('is_billable')->default(true);
            $table->timestamps();

            $table->index(['task_id', 'user_id']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
