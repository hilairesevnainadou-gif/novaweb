<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');               // Titre du service
            $table->text('description')->nullable(); // Description courte
            $table->text('full_description')->nullable(); // Description complète
            $table->string('icon')->nullable();     // Icône FontAwesome
            $table->string('icon_color')->default('#667eea'); // Couleur de l'icône
            $table->json('features')->nullable();   // Liste des fonctionnalités en JSON
            $table->integer('order')->default(0);   // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Service actif
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
