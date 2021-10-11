<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImagesTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('lang_id')->nullable();
            $table->unsignedInteger('product_image_id')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();

            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');
            $table->foreign('product_image_id')->references('id')->on('product_images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images_translation');
    }
}
