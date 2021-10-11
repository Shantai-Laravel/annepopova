<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subproducts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('parameter_id')->nullable();
            $table->unsignedInteger('value_id')->nullable();
            $table->string('code')->nullable();
            $table->string('combination')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('actual_price', 10, 2)->nullable();
            $table->unsignedInteger('discount')->nullable();
            $table->unsignedInteger('stoc')->nullable();
            $table->tinyInteger('active')->nullable();

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('cascade');
            $table->foreign('value_id')->references('id')->on('parameter_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subproducts');
    }
}
