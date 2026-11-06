<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerForeignToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('dealership_id')
                ->references('id')
                ->on('dealerships')
                ->onDelete('set null');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('dealership_id')
                ->references('id')
                ->on('dealerships')
                ->onDelete('set null');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
            $table->dropForeign('users_dealership_id_foreign');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_dealership_id_foreign');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_user_id_foreign');
        });
    }
}
