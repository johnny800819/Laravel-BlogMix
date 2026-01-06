<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id()->comment('工單ID');
            $table->foreignId('user_id')->comment('提問會員ID')->constrained()->onDelete('cascade');
            $table->string('subject')->comment('主旨');
            $table->longText('content')->comment('問題內容');
            $table->string('status')->default('open')->comment('狀態: open, replied, closed');
            $table->longText('reply_content')->nullable()->comment('管理員回覆內容');
            $table->foreignId('category_id')->nullable()->comment('問題分類ID');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
