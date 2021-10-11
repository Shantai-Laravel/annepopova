<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutometaScriptsTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autometa_scripts_translation', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedInteger('script_id')->nullable();
            $table->unsignedInteger('lang_id')->nullable();
            $table->string('var1')->nullable();
            $table->string('var2')->nullable();
            $table->string('var3')->nullable();
            $table->string('var4')->nullable();
            $table->string('var5')->nullable();
            $table->string('var6')->nullable();
            $table->string('var7')->nullable();
            $table->string('var8')->nullable();
            $table->string('var9')->nullable();
            $table->string('var10')->nullable();
            $table->string('var11')->nullable();
            $table->string('var12')->nullable();
            $table->string('var13')->nullable();
            $table->string('var14')->nullable();
            $table->string('var15')->nullable();
            $table->longText('description')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();

            $table->timestamps();

            // $table->foreign('script_id')->references('id')->on('autometa_scripts')->onDelete('cascade');
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
        Schema::dropIfExists('autometa_scripts_translation');
    }
}
