<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id()->comment('購物車項目ID');
            $table->foreignId('cart_id')->comment('購物車ID')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->comment('商品(文章)ID')->constrained('articles');
            $table->integer('quantity')->default(1)->comment('數量');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
