<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class BookingObserver
{
    public function created(Booking $booking)
    {
        try {
            Cache::forget("api.bookings.show.{$booking->id}");

            LogService::booking("BOOKING CREATED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('booking', "BOOKING ObSERVER ERROR (created)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Booking $booking)
    {
        try {
            Cache::forget("api.bookings.show.{$booking->id}");

            LogService::booking("BOOKING UPDATED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
                'changes' => $booking->getChanges(),
            ]);

            if ($booking->status === 'cancelled') {
                LogService::warning('booking', "BOOKING STATUS CHANGED TO CANCELLED", [
                    'booking_id' => $booking->id,
                    'code' => $booking->code,
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('booking', "BOOKING ObSERVER ERROR (updated)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Booking $booking)
    {
        try {
            Cache::forget("api.bookings.show.{$booking->id}");

            LogService::booking("BOOKING DELETED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('booking', "BOOKING ObSERVER ERROR (deleted)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
