<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subproduct_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->unsignedInteger('subproduct_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('old_price', 8, 2)->default(0);
            $table->boolean('dependable')->default(1);
            $table->timestamps();

            $table->foreign('subproduct_id')->references('id')->on('subproducts')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subproduct_prices');
    }
}
