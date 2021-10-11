<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutometaCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autometa_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('autometa_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->timestamps();

            $table->foreign('autometa_id')->references('id')->on('autometas')->onDelete('cascade');
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
        Schema::dropIfExists('autometa_categories');
    }
}
