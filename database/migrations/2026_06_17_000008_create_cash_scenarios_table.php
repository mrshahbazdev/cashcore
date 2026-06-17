<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('current_revenue', 12, 2)->default(0);
            $table->decimal('current_costs', 12, 2)->default(0);
            $table->decimal('adjusted_revenue', 12, 2)->default(0);
            $table->decimal('adjusted_costs', 12, 2)->default(0);
            $table->decimal('projected_profit', 12, 2)->default(0);
            $table->json('adjustments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_scenarios');
    }
};
