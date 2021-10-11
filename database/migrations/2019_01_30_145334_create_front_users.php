<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('lang')->nullable();
            $table->unsignedInteger('is_authorized')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('birthday')->nullable();
            $table->unsignedInteger('terms_agreement')->nullable();
            $table->unsignedInteger('promo_agreement')->nullable();
            $table->unsignedInteger('personaldata_agreement')->nullable();
            $table->string('company')->nullable();
            $table->string('companyaddress')->nullable();
            $table->string('fisc')->nullable();
            $table->unsignedInteger('priorityaddress')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
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
        Schema::dropIfExists('front_users');
    }
}
