<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('set_id')->default(0);
            $table->unsignedInteger('product_id')->default(0);
            $table->unsignedInteger('subproduct_id')->default(0);
            $table->unsignedInteger('qty')->nullable();
            $table->string('code')->nullable();
            $table->unsignedInteger('discount')->nullable();
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('img')->nullable();
            $table->string('details')->nullable();
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
        Schema::dropIfExists('crm_order_items');
    }
}
