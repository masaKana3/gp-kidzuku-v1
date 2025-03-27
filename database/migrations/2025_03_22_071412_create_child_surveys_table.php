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
        Schema::create('child_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('birth_date');
            $table->string('gender');
            $table->boolean('menstruation_tracking');
            $table->date('last_period_date')->nullable();
            $table->integer('sibling_order');
            $table->string('school_attendance');
            $table->boolean('regular_hospital_visit');
            $table->json('medical_departments')->nullable();
            $table->string('other_department')->nullable();
            $table->json('diagnoses')->nullable();
            $table->string('other_diagnosis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_surveys');
    }
};
