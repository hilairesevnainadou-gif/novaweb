<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussion_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_discussion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_discussion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussion_messages');
    }
};
