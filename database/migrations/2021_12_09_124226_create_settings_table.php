<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('maintenance_mode')->default(0);
            $table->string('logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('popular_category_banner', 255)->nullable();
            $table->string('featured_category_banner', 255)->nullable();
            $table->string('contact_email', 191)->nullable();
            $table->integer('enable_user_register')->default(1);
            $table->integer('enable_multivendor')->default(1);
            $table->integer('enable_subscription_notify')->default(1);
            $table->integer('enable_save_contact_message')->default(1);
            $table->string('text_direction', 255)->default('LTR');
            $table->string('timezone', 255)->nullable();
            $table->string('sidebar_lg_header', 255)->nullable();
            $table->string('sidebar_sm_header', 255)->nullable();
            $table->string('topbar_phone', 191)->nullable();
            $table->string('topbar_email', 191);
            $table->string('menu_phone', 191)->nullable();
            $table->string('currency_name', 191)->nullable();
            $table->string('currency_icon', 191)->nullable();
            $table->double('currency_rate')->default(1);
            $table->boolean('show_product_qty')->default(1);
            $table->string('theme_one', 191);
            $table->string('theme_two', 191);
            $table->longText('seller_condition')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
