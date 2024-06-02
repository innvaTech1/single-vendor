<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('slug')->unique();
            $table->string('thumb_image');
            $table->unsignedInteger('vendor_id')->default(0);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('sub_category_id')->default(0);
            $table->unsignedInteger('child_category_id')->default(0);
            $table->unsignedInteger('brand_id')->default(0);
            $table->unsignedInteger('qty')->default(0);
            $table->string('weight')->default('0');
            $table->unsignedInteger('sold_qty')->default(0);
            $table->text('short_description');
            $table->longText('long_description');
            $table->string('video_link')->nullable();
            $table->string('sku')->nullable();
            $table->text('seo_title');
            $table->text('seo_description');
            $table->double('price');
            $table->double('offer_price')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('show_homepage')->default(0);
            $table->tinyInteger('is_undefine')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('new_product')->default(0);
            $table->tinyInteger('is_top')->default(0);
            $table->tinyInteger('is_best')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('is_specification')->default(1);
            $table->tinyInteger('approve_by_admin')->default(0);
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
        Schema::dropIfExists('products');
    }
}
