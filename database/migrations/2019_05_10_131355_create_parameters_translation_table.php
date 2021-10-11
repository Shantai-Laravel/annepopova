<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('lang_id')->nullable();
            $table->unsignedInteger('parameter_id')->nullable();
            $table->string('name')->nullable();
            $table->string('unit')->nullable();

            $table->timestamps();

            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters_translation');
    }
}
