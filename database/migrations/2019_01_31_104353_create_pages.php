<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('gallery_id')->nullable();
            $table->string('alias')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('on_header')->nullable();
            $table->tinyInteger('on_drop_down')->nullable();
            $table->tinyInteger('on_footer')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->tinyInteger('active')->default(1);
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
        Schema::dropIfExists('pages');
    }
}
