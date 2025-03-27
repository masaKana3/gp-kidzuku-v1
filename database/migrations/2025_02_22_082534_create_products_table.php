<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // ⭐️↓ この3行追加
            $table->string('name'); // 商品名
            $table->text('description')->nullable(); // 商品の詳細（NULL可）
            $table->integer('price'); // 価格（整数）
            // ⭐️↑ この3行追加
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
