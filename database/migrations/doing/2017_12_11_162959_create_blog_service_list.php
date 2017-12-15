<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogServiceList extends Migration
{
    public function up()
    {
        Schema::create('blog_service_list', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('slist_id');
            $table->integer('slist_status')->comment('客服狀態 0＝未回覆 1＝處理中 2＝已回覆');
            $table->string('slist_time',50)->comment('發問時間');
            $table->integer('slist_user_id')->comment('發問人id');
            $table->string('slist_ip',50)->comment('發問人ip');
            $table->text('slist_theme')->comment('問題主旨');
            $table->text('slist_question')->comment('問題內容');
            $table->text('slist_response')->comment('回覆內容');
            $table->timestamps();
        });

        //php artisan migrate --path=/database/migrations/single
    }

    public function down()
    {
        Schema::drop('blog_service_list');
    }
}
