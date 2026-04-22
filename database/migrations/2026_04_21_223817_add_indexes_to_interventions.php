<?php
// database/migrations/2024_01_01_000002_add_indexes_to_interventions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->index('end_date');
            $table->index(['status', 'end_date']);
        });
    }

    public function down()
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropIndex(['end_date']);
            $table->dropIndex(['status', 'end_date']);
        });
    }
};
