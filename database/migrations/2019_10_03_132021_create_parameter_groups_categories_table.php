<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterGroupsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_groups_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('parameter_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameter_groups_categories');
    }
}
