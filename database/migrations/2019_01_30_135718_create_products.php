<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('promotion_id')->nullable();
            $table->string('alias')->nullable();
            $table->string('code')->nullable();
            $table->string('video')->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('actual_price', 10, 2)->nullable();
            $table->string('discount')->nullable();
            $table->string('discount_update')->nullable();
            $table->tinyInteger('hit')->nullable();
            $table->tinyInteger('recomended')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('succesion')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
