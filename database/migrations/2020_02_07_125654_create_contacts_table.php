<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('address')->nullable();
            $table->string('geo_loc1')->nullable();
            $table->string('geo_loc2')->nullable();
            $table->string('geo_loc3')->nullable();
            $table->tinyInteger('type')->unsigned()->nullable();
            $table->tinyInteger('source')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_subscribed')->default(1);
            $table->unsignedInteger('dealership_id')->nullable();
            $table->string('localization')->default('cl');
            $table->timestamps();

            $table->foreign('dealership_id')
                ->references('id')
                ->on('dealerships');
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
        Schema::dropIfExists('contacts');
    }
}
