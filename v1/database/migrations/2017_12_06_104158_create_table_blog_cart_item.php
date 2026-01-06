<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlogCartItem extends Migration
{
    public function up()
    {
        Schema::create('blog_cart_item', function (Blueprint $table) {
            $table->increments('item_id');
            $table->integer('cart_id')->comment('在哪台購物車中');
            $table->integer('product_id')->comment('購物車中的商品ID');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('cart_items');
    }
}
