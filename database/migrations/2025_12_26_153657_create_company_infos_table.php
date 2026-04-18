<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInfosTable extends Migration
{
    public function up()
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();

            // Identité & Informations de base
            $table->string('name')->nullable();           // Nom de la société
            $table->string('slogan')->nullable();         // Slogan de l'entreprise

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            // Médias & Images
            $table->string('logo')->nullable();          // Chemin du logo
            $table->string('banner_image')->nullable();  // Image du bannière
            $table->string('about_image')->nullable();   // Image à propos

            // Banner / Hero Section
            $table->string('hero_title')->nullable();    // Titre principal
            $table->text('hero_description')->nullable(); // Description principale

            // About Section
            $table->string('about_title')->nullable();   // Titre section à propos
            $table->text('about_description_1')->nullable(); // 1ère description à propos
            $table->text('about_description_2')->nullable(); // 2ème description à propos

            // Réseaux sociaux
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            // Statistiques & Données
            $table->integer('years_experience')->default(0); // Années d'expérience

            // Horaires d'ouverture
            $table->string('opening_hours')->nullable();     // Horaires semaine
            $table->string('opening_hours_weekend')->nullable(); // Horaires week-end

            // Localisation
            $table->decimal('latitude', 10, 8)->nullable();   // Latitude GPS
            $table->decimal('longitude', 11, 8)->nullable();  // Longitude GPS
            $table->text('google_maps_url')->nullable();      // URL Google Maps

            // Informations supplémentaires
            $table->string('website')->nullable();            // Site web
            $table->text('mission')->nullable();              // Mission de l'entreprise
            $table->text('vision')->nullable();               // Vision de l'entreprise
            $table->text('values')->nullable();               // Valeurs de l'entreprise

            // Métadonnées
            $table->text('meta_description')->nullable();     // Description SEO
            $table->string('meta_keywords')->nullable();      // Mots-clés SEO

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_infos');
    }
}
