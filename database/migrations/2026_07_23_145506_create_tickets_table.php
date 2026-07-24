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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->nullable()->constrained('passengers')->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->string('seat_number')->nullable();
            $table->enum('class', [
                'economy',
                'premium_economy',
                'business',
                'first'
            ])->default('economy');
            $table->enum('meal_preference', [
                'standard',
                'full_meal',
                'sandwitch',
                'child_meal',
                'none'
            ])->default('standard');
            $table->timestamp('issued_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
