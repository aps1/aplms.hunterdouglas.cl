<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactMethodToContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('contact_method')->nullable()->after('phone2');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->string('contact_method')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('contact_method');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('contact_method');
        });
    }
}
