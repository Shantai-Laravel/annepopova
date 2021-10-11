<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImagesTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_images_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->unsignedInteger('lang_id')->nullable();
            $table->unsignedInteger('gallery_image_id')->nullable();
            $table->string('text')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();

            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');
            $table->foreign('gallery_image_id')->references('id')->on('gallery_images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_images_translation');
    }
}
