<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->type_tables() as $type_table) {
            Schema::create($type_table, function (Blueprint $table) {
                $table->integer('id');
                $table->string('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->type_tables() as $type_table) {
            Schema::dropIfExists($type_table);
        }
    }

    protected function type_tables(): array
    {
        return [
            'activity_types',
            'contact_sources',
            'contact_types',
            'discard_reasons',
            'lead_origins',
            'lead_priorities',
            'lead_sources',
            'lead_status',
            'product_categories',
            'user_types',
        ];
    }
}
