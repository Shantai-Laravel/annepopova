<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_sets', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('return_id')->nullable();
            $table->unsignedInteger('set_id')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->decimal('price', 10, 2)->nullable();
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
        Schema::dropIfExists('return_sets');
    }
}
