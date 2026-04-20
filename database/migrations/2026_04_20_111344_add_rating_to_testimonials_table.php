<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Ajouter la colonne rating si elle n'existe pas déjà
            if (!Schema::hasColumn('testimonials', 'rating')) {
                $table->integer('rating')->nullable()->after('content');
            }

            // Ajouter les autres colonnes manquantes si nécessaire
            if (!Schema::hasColumn('testimonials', 'avatar')) {
                $table->string('avatar')->nullable()->after('rating');
            }

            if (!Schema::hasColumn('testimonials', 'order')) {
                $table->integer('order')->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['rating', 'avatar', 'order']);
        });
    }
};
