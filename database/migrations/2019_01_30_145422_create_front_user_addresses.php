<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontUserAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_user_addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('front_user_id')->nullable();
            $table->string('addressname')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('code')->nullable();
            $table->string('homenumber')->nullable();
            $table->string('entrance')->nullable();
            $table->string('floor')->nullable();
            $table->string('apartment')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('front_user_id')->references('id')->on('front_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_user_addresses');
    }
}
