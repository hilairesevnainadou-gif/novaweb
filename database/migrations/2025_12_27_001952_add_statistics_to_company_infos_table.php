<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatisticsToCompanyInfosTable extends Migration
{
    public function up()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            // Statistiques
            $table->integer('web_development_percentage')->default(90);
            $table->integer('it_maintenance_percentage')->default(85);
            $table->integer('client_satisfaction_percentage')->default(95);
            $table->integer('happy_clients_count')->default(150);
            $table->integer('projects_completed')->default(200);
            $table->integer('support_hours')->default(24);
        });
    }

    public function down()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn([
                'web_development_percentage',
                'it_maintenance_percentage',
                'client_satisfaction_percentage',
                'happy_clients_count',
                'projects_completed',
                'support_hours',
            ]);
        });
    }
}
