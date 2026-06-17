<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_transaction_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->nullable();
            $table->string('benefit')->nullable();
            $table->integer('revenue_score')->default(0);
            $table->integer('efficiency_score')->default(0);
            $table->integer('strategic_score')->default(0);
            $table->integer('usage_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->string('recommendation')->default('keep');
            $table->timestamps();

            $table->unique('cash_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_scores');
    }
};
