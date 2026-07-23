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
        Schema::create('flights', function (Blueprint $table) {
            $table->string('flight_number')->primary();
            $table->string('airline');
            $table->foreignId('origin_airport_id')->constrained('airports')->onDelete('cascade');
            $table->foreignId('airplane_id')->constrained('airplanes')->onDelete('cascade');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->integer('booked_seats')->default(0);
            $table->integer('available_seats')->default(0);
            $table->enum('status', [
                'scheduled', 'open', 'closing', 
                'completed', 'cancelled', 'delayed', 
                'boarding', 'departed'
            ])->default('scheduled');
            $table->timestamp('booking_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
