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
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('passenger_id')->nullable()->constrained('passengers')->onDelete('set null');
            $table->string('ticket_number')->unique();

            //denormalization
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('seat_number')->nullable();
            $table->enum('status', [
                'issued',        
                'checked_in',   
                'boarded',      
                'used',          
                'cancelled',   
                'refunded',     
            ])->default('issued');
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('boarded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
