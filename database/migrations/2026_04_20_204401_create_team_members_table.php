<?php
// database/migrations/2026_01_20_000002_create_team_members_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();

            // Informations personnelles
            $table->string('name');                     // Nom complet
            $table->string('position');                 // Poste/Fonction
            $table->string('photo')->nullable();        // Photo de profil
            $table->text('bio')->nullable();            // Biographie
            $table->text('quote')->nullable();          // Citation personnelle

            // Compétences
            $table->json('skills')->nullable();         // Liste des compétences (array)

            // Réseaux sociaux
            $table->string('email')->nullable();        // Email professionnel
            $table->string('linkedin')->nullable();     // Profil LinkedIn
            $table->string('github')->nullable();       // Profil GitHub
            $table->string('twitter')->nullable();      // Profil Twitter
            $table->string('facebook')->nullable();     // Profil Facebook

            // Affichage
            $table->integer('order')->default(0);       // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Membre actif
            $table->boolean('is_featured')->default(false); // Membre en vedette

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_members');
    }
};
