<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('collection_id')->nullable();
            $table->string('alias')->nullable();
            $table->string('code')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('discount')->nullable();
            $table->tinyInteger('on_home')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets');
    }
}
