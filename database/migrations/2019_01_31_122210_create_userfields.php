<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserfields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userfields', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('field');
            $table->string('type');
            $table->string('field_group');
            $table->string('value');
            $table->unsignedInteger('in_register')->nullable();
            $table->unsignedInteger('in_cabinet')->nullable();
            $table->unsignedInteger('in_cart')->nullable();
            $table->unsignedInteger('in_auth')->nullable();
            $table->unsignedInteger('unique_field')->nullable();
            $table->unsignedInteger('required_field')->nullable();
            $table->unsignedInteger('return_field')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userfields');
    }
}
