<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('is_active')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('delivery')->nullable();
            $table->string('payment')->nullable();
            $table->text('motive')->nullable();
            $table->dateTime('datetime')->useCurrent();
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
        Schema::dropIfExists('returns');
    }
}
