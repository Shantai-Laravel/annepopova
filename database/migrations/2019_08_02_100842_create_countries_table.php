<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('iso')->nullable();
            $table->string('name')->nullable();
            $table->string('nice_name')->nullable();
            $table->string('iso3')->nullable();
            $table->unsignedInteger('num_code')->nullable();
            $table->unsignedInteger('phone_code')->nullable();
            $table->string('flag')->nullable();
            $table->string('vat')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('main')->default(0);
            $table->unsignedInteger('lang_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();

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
        Schema::dropIfExists('countries');
    }
}
