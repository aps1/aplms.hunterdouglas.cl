<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->unsignedInteger('user_id')->nullable(); // vendedor. puede no tener
            $table->unsignedInteger('dealership_id')->nullable(); // vendedor. puede no tener
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads');
            $table->foreign('dealership_id')
                ->references('id')
                ->on('dealerships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
