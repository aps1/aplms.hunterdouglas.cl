<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFolios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folios', function (Blueprint $table) {
            $table->string('status')->nullable()->after('lead_id');
            $table->boolean('is_active')->default(1)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folios', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_active');
        });
    }
}
