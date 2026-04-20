<?php
// database/migrations/2026_01_20_000001_add_legal_fields_to_company_infos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            // Mentions légales - Informations juridiques
            $table->string('legal_form')->nullable()->after('values');
            $table->string('capital')->nullable()->after('legal_form');
            $table->string('rccm')->nullable()->after('capital');
            $table->string('ifu')->nullable()->after('rccm');
            $table->string('director')->nullable()->after('ifu');
            $table->string('legal_address')->nullable()->after('director');

            // Mentions légales - Hébergement
            $table->string('hosting_name')->nullable()->after('legal_address');
            $table->string('hosting_address')->nullable()->after('hosting_name');
            $table->string('hosting_phone')->nullable()->after('hosting_address');
            $table->string('hosting_url')->nullable()->after('hosting_phone');

            // Mentions légales - Suppléments
            $table->string('data_protection_officer')->nullable()->after('hosting_url');
            $table->string('cnie_number')->nullable()->after('data_protection_officer');
            $table->string('trade_register')->nullable()->after('cnie_number');
            $table->string('vat_number')->nullable()->after('trade_register');
        });
    }

    public function down()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'legal_form', 'capital', 'rccm', 'ifu', 'director', 'legal_address',
                'hosting_name', 'hosting_address', 'hosting_phone', 'hosting_url',
                'data_protection_officer', 'cnie_number', 'trade_register', 'vat_number'
            ]);
        });
    }
};
