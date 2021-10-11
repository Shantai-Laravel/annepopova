<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_order_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('set_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('subproduct_id')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->string('code')->nullable();
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('img')->nullable();
            $table->boolean('deleted')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('crm_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_order_products');
    }
}
