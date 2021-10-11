<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('promocode_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedInteger('delivery_id')->nullable();
            $table->decimal('amount', 10 ,2)->nullable();
            $table->string('main_status')->nullable();
            $table->string('secondary_status')->nullable();
            $table->dateTime('change_status_at')->nullable();

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
        Schema::dropIfExists('crm_orders');
    }
}
