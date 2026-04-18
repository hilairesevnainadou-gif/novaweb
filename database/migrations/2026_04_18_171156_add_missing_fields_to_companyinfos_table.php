<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToCompanyInfosTable extends Migration
{
    public function up()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            // Ajouter favicon si pas présent
            if (!Schema::hasColumn('company_infos', 'favicon')) {
                $table->string('favicon')->nullable()->after('logo');
            }

            // Ajouter description générale
            if (!Schema::hasColumn('company_infos', 'description')) {
                $table->text('description')->nullable()->after('slogan');
            }

            // Ajouter meta_keywords (alias de site_keywords)
            if (!Schema::hasColumn('company_infos', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
        });
    }

    public function down()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn(['favicon', 'description', 'meta_keywords']);
        });
    }
}
