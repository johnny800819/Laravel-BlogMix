<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('訂單ID');
            $table->foreignId('user_id')->comment('會員ID')->constrained()->onDelete('cascade');
            $table->string('status', 191)->default('pending')->comment('狀態: pending, paid, shipped, cancelled');
            $table->decimal('total_amount', 10, 2)->comment('總金額');
            $table->string('receiver_name', 191)->comment('收件人姓名');
            $table->string('receiver_phone', 191)->comment('收件人電話');
            $table->text('shipping_address')->comment('收件地址');
            $table->string('payment_method', 191)->nullable()->comment('付款方式');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
