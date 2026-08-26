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

            LogService::system("booking CREATED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "booking ObSERVER ERROR (created)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Booking $booking)
    {
        try {
            Cache::forget("api.bookings.show.{$booking->id}");

            LogService::system("booking UPDATED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
                'changes' => $booking->getChanges(),
            ]);

            if ($booking->status === 'cancelled') {
                LogService::warning('system', "booking STATUS CHANGED TO CANCELLED", [
                    'booking_id' => $booking->id,
                    'code' => $booking->code,
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "booking ObSERVER ERROR (updated)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Booking $booking)
    {
        try {
            Cache::forget("api.bookings.show.{$booking->id}");

            LogService::system("booking DELETED: Cache cleared", [
                'booking_id' => $booking->id,
                'code' => $booking->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "booking ObSERVER ERROR (deleted)", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
