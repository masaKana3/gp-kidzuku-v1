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
        Schema::create('parent_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('birth_date');
            $table->string('gender');
            $table->boolean('health_concern');
            $table->integer('health_rating');
            $table->json('concerns')->nullable();
            $table->json('consultants')->nullable(); // 相談相手（複数選択可）
            $table->string('other_consultant')->nullable(); // その他の相談相手（自由回答）
            $table->boolean('regular_hospital_visit');
            $table->json('medical_departments')->nullable();
            $table->string('other_department')->nullable();
            $table->boolean('menstruation_tracking');
            $table->date('last_period_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_surveys');
    }
};
