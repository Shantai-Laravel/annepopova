<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('page_id')->nullable();
            $table->unsignedInteger('lang_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');

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
        Schema::dropIfExists('pages_translation');
    }
}
