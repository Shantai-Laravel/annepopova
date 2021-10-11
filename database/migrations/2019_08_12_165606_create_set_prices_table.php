<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->unsignedInteger('set_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('old_price', 8, 2)->nullable();
            $table->boolean('dependable')->default(1);
            $table->timestamps();

            $table->foreign('set_id')->references('id')->on('sets')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_prices');
    }
}
