<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('discount')->nullable();
            $table->string('valid_from')->nullable();
            $table->string('valid_to')->nullable();
            $table->unsignedInteger('period')->nullable();
            $table->decimal('treshold', 10, 2)->nullable();
            $table->unsignedInteger('to_use')->nullable();
            $table->unsignedInteger('times')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('promocode_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
