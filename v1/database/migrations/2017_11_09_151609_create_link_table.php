<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('blog_links', function (Blueprint $table) {
//            $table->engine = 'MyISAM';
//            $table->increments('link_id');
//            $table->string('link_title');
//            $table->string('link_description');
//            $table->string('link_url');
//            $table->integer('link_order');
//        });

        Schema::table('blog_links', function ($table) {
            $table->increments('link_id')->change();
            $table->string('link_title')->comment('標題')->change();
            $table->string('link_description')->comment('簡述')->change();
            $table->string('link_url')->comment('連結')->change();
            $table->integer('link_order')->comment('排序')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blog_links');
    }
}
