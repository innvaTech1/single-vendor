<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSslcommerzPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sslcommerz_payments', function (Blueprint $table) {
            $table->id();
            $table->text('store_id')->nullable();
            $table->text('store_password')->nullable();
            $table->string('mode', 255)->nullable();
            $table->string('currency_rate', 255)->nullable();
            $table->string('country_code', 255)->nullable();
            $table->string('currency_code', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->integer('currency_id')->nullable();
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
        Schema::dropIfExists('sslcommerz_payments');
    }
}
