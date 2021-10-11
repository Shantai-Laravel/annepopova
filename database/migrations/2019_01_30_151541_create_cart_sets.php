<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_sets', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('set_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->tinyInteger('is_logged')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('set_id')->references('id')->on('sets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_sets');
    }
}
