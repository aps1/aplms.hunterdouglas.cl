<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandFieldsToDealerships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealerships', function (Blueprint $table) {
            $table->boolean('wcp1')->default(1)->after('email');
            $table->boolean('wcp2')->default(0)->after('wcp1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealerships', function (Blueprint $table) {
            $table->dropColumn('wcp1');
            $table->dropColumn('wcp2');
        });
    }
}
