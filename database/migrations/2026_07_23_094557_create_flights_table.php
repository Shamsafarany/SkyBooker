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
            $table->id();
            $table->string('flight_number')->unique();
            $table->foreignId('origin_airport_id')->constrained('airports')->onDelete('cascade');
            $table->foreignId('destination_airport_id')->constrained('airports')->onDelete('cascade');
            $table->foreignId('airplane_id')->constrained('airplanes')->onDelete('cascade');
            $table->foreignId('airline_id')->constrained('airlines')->onDelete('cascade');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->integer('booked_seats')->default(0);
            $table->integer('available_seats')->default(0);
            $table->enum('status', [
                'new','scheduled', 'open', 'closing', 
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
