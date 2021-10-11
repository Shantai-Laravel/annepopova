<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutometas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autometas', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('meta_id')->nullable();
            $table->unsignedInteger('lang_id')->nullable();
            $table->string('name')->nullable();
            $table->text('seotext')->nullable();
            $table->text('product_description')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->text('var1')->nullable();
            $table->text('var2')->nullable();
            $table->text('var3')->nullable();
            $table->text('var4')->nullable();
            $table->text('var5')->nullable();
            $table->text('var6')->nullable();
            $table->text('var7')->nullable();
            $table->text('var8')->nullable();
            $table->text('var9')->nullable();
            $table->text('var10')->nullable();
            $table->text('var11')->nullable();
            $table->text('var12')->nullable();
            $table->text('var13')->nullable();
            $table->text('var14')->nullable();
            $table->text('var15')->nullable();
            $table->timestamps();

            $table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autometas');
    }
}
