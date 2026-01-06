<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id()->comment('購物車ID');
            $table->foreignId('user_id')->nullable()->comment('會員ID (訪客為空)')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->comment('訪客 Session ID');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
