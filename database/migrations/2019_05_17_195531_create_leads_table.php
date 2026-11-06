<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('correlative')->nullable(); // el id de la db unificada
            $table->string('web_identifier')->nullable(); // el correlativo con el pais
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country');
            $table->string('address')->nullable();
            $table->string('geo_loc1')->nullable();
            $table->string('geo_loc2')->nullable();
            $table->string('geo_loc3')->nullable();
            $table->string('brand')->nullable();
            $table->tinyInteger('origin')->nullable();
            $table->timestamp('date');
            $table->boolean('is_subscribed')->default(1);
            $table->text('product')->nullable();
            $table->string('dealersite')->nullable();
            $table->unsignedInteger('dealership_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('page_title', 300)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('priority')->nullable();
            $table->text('message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
