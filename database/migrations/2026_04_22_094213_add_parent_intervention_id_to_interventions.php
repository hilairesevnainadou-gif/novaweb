<?php
// database/migrations/2024_01_01_000006_add_parent_intervention_id_to_interventions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->foreignId('parent_intervention_id')->nullable()->after('id')->constrained('interventions')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropForeign(['parent_intervention_id']);
            $table->dropColumn('parent_intervention_id');
        });
    }
};
