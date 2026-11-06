<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalizationFieldsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('localization')->after('password')->default('cl');
            $table->index('localization');
        });
        Schema::table('dealerships', function (Blueprint $table) {
            $table->string('localization')->after('is_active')->default('cl');
            $table->index('localization');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->string('localization')->after('message')->default('cl');
            $table->index('localization');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->string('localization')->after('observation')->default('cl');
            $table->index('localization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('localization');
        });
        Schema::table('dealerships', function (Blueprint $table) {
            $table->dropColumn('localization');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('localization');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('localization');
        });
    }
}
