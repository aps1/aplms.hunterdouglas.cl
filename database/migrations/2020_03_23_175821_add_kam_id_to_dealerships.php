<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKamIdToDealerships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealerships', function (Blueprint $table) {
            $table->unsignedInteger('kam_id')->nullable()->after('is_active');

            $table->foreign('kam_id')
                ->references('id')
                ->on('users');
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
            $table->dropForeign('dealerships_kam_id_foreign');
            $table->dropColumn('kam_id');
        });
    }
}
