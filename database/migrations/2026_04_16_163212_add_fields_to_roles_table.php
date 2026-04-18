<?php
// database/migrations/2024_01_01_000004_add_columns_to_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description']);
        });
    }
};
