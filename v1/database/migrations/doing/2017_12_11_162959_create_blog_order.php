<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogOrder extends Migration
{
    public function up()
    {
        Schema::create('blog_order', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('order_id');
            $table->string('order_date',11)->comment('訂單日期');
            $table->string('order_money',11)->comment('訂單金額');
            $table->integer('order_invoice')->comment('訂單發票方式');
            $table->string('order_payment',11)->comment('訂單付款方式');
            $table->integer('order_payment_state')->comment('訂單付款狀態');
            $table->string('order_uni_number',11)->comment('訂單統一編號');
            $table->timestamps();
        });
        //php artisan migrate --path=/database/migrations/doing
    }

    public function down()
    {
        Schema::drop('blog_order');
    }
}
