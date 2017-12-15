<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlogCart extends Migration
{
    public function up()
    {
        Schema::create('blog_cart', function (Blueprint $table) {
            $table->increments('cart_id');
            $table->integer('cart_user_id')->comment('購物車使用者');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('blog_cart');
    }
}
