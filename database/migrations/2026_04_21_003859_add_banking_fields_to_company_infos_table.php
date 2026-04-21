<?php
// database/migrations/2026_04_21_004000_add_banking_fields_to_company_infos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankingFieldsToCompanyInfosTable extends Migration
{
    public function up()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà
            if (!Schema::hasColumn('company_infos', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'bank_iban')) {
                $table->string('bank_iban')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'bank_swift')) {
                $table->string('bank_swift')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'mobile_money_number')) {
                $table->string('mobile_money_number')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'mobile_money_operator')) {
                $table->string('mobile_money_operator')->nullable();
            }
            if (!Schema::hasColumn('company_infos', 'payment_instructions')) {
                $table->text('payment_instructions')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $columns = [
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'bank_iban',
                'bank_swift',
                'mobile_money_number',
                'mobile_money_operator',
                'payment_instructions'
            ];

            $table->dropColumn($columns);
        });
    }
}
