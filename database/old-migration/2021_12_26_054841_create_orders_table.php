<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->unsignedBigInteger('user_id');
            $table->double('total_amount', 8, 2)->default(0);
            $table->integer('product_qty');
            $table->string('payment_method')->nullable();
            $table->integer('payment_status')->default(0);
            $table->string('payment_approval_date')->nullable();
            $table->string('transection_id')->nullable();
            $table->string('shipping_method')->nullable();
            $table->double('shipping_cost', 8, 2)->default(0);
            $table->double('coupon_coast', 8, 2)->default(0);
            $table->integer('order_status')->default(0);
            $table->string('order_approval_date')->nullable();
            $table->string('order_delivered_date')->nullable();
            $table->string('order_completed_date')->nullable();
            $table->string('order_declined_date')->nullable();
            $table->integer('cash_on_delivery')->default(0);
            $table->text('additional_info')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
