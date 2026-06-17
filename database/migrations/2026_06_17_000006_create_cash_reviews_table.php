<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('review_type')->default('monthly');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->text('checklist')->nullable();
            $table->integer('streak_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_reviews');
    }
};
