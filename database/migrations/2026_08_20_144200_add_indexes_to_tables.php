<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->index('flight_number');
            $table->index('departure_date');
            $table->index('status');
            $table->index('airline_id');
            $table->index('origin_airport_id');
            $table->index('destination_airport_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('booking_reference');
            $table->index('user_id');
            $table->index('flight_id');
        });
        Schema::table('passengers', function (Blueprint $table) {
            $table->index('booking_id');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('ticket_number');
            $table->index('passenger_id');
        });
        Schema::table('airlines', function (Blueprint $table) {
            $table->index('code');
        });

        Schema::table('airports', function (Blueprint $table) {
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropIndex(['flight_number']);
            $table->dropIndex(['departure_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['airline_id']);
            $table->dropIndex(['origin_airport_id']);
            $table->dropIndex(['destination_airport_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_reference']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['flight_id']);
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['ticket_number']);
            $table->dropIndex(['passenger_id']);
        });

        Schema::table('airlines', function (Blueprint $table) {
            $table->dropIndex(['code']);
        });

        Schema::table('airports', function (Blueprint $table) {
            $table->dropIndex(['code']);
        });
    }
};
