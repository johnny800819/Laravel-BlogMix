<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id()->comment('文章ID');
            $table->foreignId('category_id')->comment('主分類ID')->constrained('categories');
            $table->foreignId('category_sub_id')->nullable()->comment('子分類ID')->constrained('categories');
            $table->string('title')->comment('標題');
            $table->longText('content')->comment('內容');
            $table->string('image_path')->nullable()->comment('封面圖片路徑');
            $table->decimal('price', 10, 2)->default(0)->comment('價格');
            $table->integer('view_count')->default(0)->comment('瀏覽次數');
            $table->boolean('is_published')->default(true)->comment('是否發布');
            $table->timestamp('published_at')->nullable()->comment('發布時間');
            $table->timestamps();
            $table->softDeletes()->comment('軟刪除時間');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
