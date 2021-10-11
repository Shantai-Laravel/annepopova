<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('lang_id')->nullable();
            $table->unsignedInteger('delivery_id')->nullable();
            $table->string('name')->nullable();

            $table->timestamps();

            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries_translation');
    }
}
