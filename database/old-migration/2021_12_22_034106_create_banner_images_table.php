<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('banner_location')->nullable();
            $table->string('header')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('after_product_qty')->default(0);
            $table->string('title_one')->nullable();
            $table->string('title_two')->nullable();
            $table->string('badge')->nullable();
            $table->text('product_slug')->nullable();
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
        Schema::dropIfExists('banner_images');
    }
}
