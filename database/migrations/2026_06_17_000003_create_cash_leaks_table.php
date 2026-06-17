<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_leaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('leak_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('monthly_amount', 12, 2)->default(0);
            $table->integer('leak_score')->default(0);
            $table->string('status')->default('detected');
            $table->text('recommendation')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_leaks');
    }
};
