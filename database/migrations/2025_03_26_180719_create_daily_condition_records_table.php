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
        Schema::create('daily_condition_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_survey_id')->constrained()->onDelete('cascade');
            $table->date('record_date');
            
            // 全員共通の質問項目
            $table->integer('mood_rating')->nullable()->comment('今日の気分（1〜5）');
            $table->boolean('woke_up_well')->nullable()->comment('朝起きられたか');
            $table->boolean('body_fatigue')->nullable()->comment('身体がだるい');
            $table->boolean('sleep_quality')->nullable()->comment('睡眠の質');
            $table->boolean('headache')->nullable()->comment('頭痛');
            $table->boolean('stomachache')->nullable()->comment('腹痛');
            
            // ODS・生理周期管理の人向け質問
            $table->boolean('dizziness')->nullable()->comment('めまい・立ちくらみ');
            
            // ODS向け質問
            $table->integer('blood_pressure_high')->nullable()->comment('血圧（上）');
            $table->integer('blood_pressure_low')->nullable()->comment('血圧（下）');
            
            // 生理周期管理向け質問
            $table->boolean('irritability')->nullable()->comment('イライラ・怒りっぽさ');
            $table->boolean('depression')->nullable()->comment('気分の落ち込み');
            
            // 生理周期管理かつ女性向け質問
            $table->boolean('is_menstruating')->nullable()->comment('月経中か');
            $table->string('blood_amount')->nullable()->comment('血液量（少ない/普通/多い）');
            
            // 天気情報
            $table->string('weather')->nullable()->comment('天気');
            $table->float('temperature')->nullable()->comment('気温');
            $table->float('pressure')->nullable()->comment('気圧');
            
            $table->text('notes')->nullable()->comment('備考');
            $table->timestamps();
            
            // インデックス
            $table->index(['child_survey_id', 'record_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_condition_records');
    }
};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_condition_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_condition_records');
    }
};
