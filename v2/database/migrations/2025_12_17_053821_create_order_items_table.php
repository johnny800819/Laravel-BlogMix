<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id()->comment('訂單項目ID');
            $table->foreignId('order_id')->comment('所屬訂單ID')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->comment('商品(文章)ID')->constrained('articles');
            $table->integer('quantity')->default(1)->comment('數量');
            $table->decimal('price_at_purchase', 10, 2)->comment('購買時單價');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
