<?php

namespace App\Observers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class TicketObserver
{
    public function created(Ticket $ticket)
    {
        try {
            Cache::forget("api.tickets.show.{$ticket->id}");

            LogService::system("TICKET CREATED", [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'passenger_id' => $ticket->passenger_id,
                'status' => $ticket->status,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "TICKET OBSERVER ERROR (created)", [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Ticket $ticket)
    {
        try {
            Cache::forget("api.tickets.show.{$ticket->id}");

            $changes = $ticket->getChanges();

            LogService::system("TICKET UPDATED", [
                'ticket_id' => $ticket->id,
                'changes' => $changes,
            ]);

            if (array_key_exists('status', $changes)) {
                LogService::warning('system', "TICKET STATUS CHANGED", [
                    'ticket_id' => $ticket->id,
                    'old_status' => $ticket->getOriginal('status'),
                    'new_status' => $changes['status'],
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "TICKET OBSERVER ERROR (updated)", [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Ticket $ticket)
    {
        try {
            Cache::forget("api.tickets.show.{$ticket->id}");

            LogService::system("TICKET DELETED", [
                'ticket_id' => $ticket->id,
                'passenger_id' => $ticket->passenger_id,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "TICKET OBSERVER ERROR (deleted)", [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
