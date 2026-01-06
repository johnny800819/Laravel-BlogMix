<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('分類ID');
            $table->foreignId('parent_id')->nullable()->comment('父分類ID')->constrained('categories')->nullOnDelete();
            $table->string('name')->comment('分類名稱');
            $table->string('slug')->nullable()->comment('網址代稱');
            $table->string('type')->default('article')->comment('分類類型: article, product');
            $table->integer('sort_order')->default(0)->comment('排序權重');
            $table->boolean('status')->default(true)->comment('啟用狀態');
            $table->timestamps();
            $table->softDeletes()->comment('軟刪除時間');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
