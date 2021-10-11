<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('alias')->nullable();
            $table->tinyInteger('level')->nullable();
            $table->tinyInteger('on_home')->nullable();
            $table->tinyInteger('position')->nullable();
            $table->tinyInteger('succesion')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('icon')->default(1);
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
        Schema::dropIfExists('blog_categories');
    }
}
