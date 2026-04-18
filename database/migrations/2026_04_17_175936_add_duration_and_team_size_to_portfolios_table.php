<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('duration')->nullable()->default('2-3 semaines')->after('order');
            $table->string('team_size')->nullable()->default('2-3 personnes')->after('duration');
        });
    }

    public function down()
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['duration', 'team_size']);
        });
    }
};
