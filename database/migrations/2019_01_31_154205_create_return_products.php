<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('return_id')->nullable();
            $table->unsignedInteger('orderProduct_id')->nullable();
            $table->unsignedInteger('set_id')->default('0');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('subproduct_id')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_products');
    }
}
