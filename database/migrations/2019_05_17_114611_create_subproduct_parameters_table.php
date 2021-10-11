<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subproduct_parameters', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('parameter_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
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
        Schema::dropIfExists('subproduct_parameters');
    }
}
