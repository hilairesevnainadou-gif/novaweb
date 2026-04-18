<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'display_name')) {
                $table->string('display_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('roles', 'description')) {
                $table->text('description')->nullable()->after('display_name');
            }
            if (!Schema::hasColumn('roles', 'color')) {
                $table->string('color')->nullable()->default('#6366f1')->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description', 'color']);
        });
    }
};
