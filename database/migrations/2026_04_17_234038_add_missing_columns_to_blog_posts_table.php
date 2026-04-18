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
        Schema::table('blog_posts', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->string('reading_time')->nullable()->after('content');
            $table->string('meta_keywords')->nullable()->after('image');
            $table->text('meta_description')->nullable()->after('meta_keywords');
            $table->boolean('is_featured')->default(false)->after('is_published');

            // Modifier le type de published_at pour accepter datetime au lieu de date
            $table->dateTime('published_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'reading_time',
                'meta_keywords',
                'meta_description',
                'is_featured'
            ]);

            // Revenir au type date pour published_at
            $table->date('published_at')->nullable()->change();
        });
    }
};
