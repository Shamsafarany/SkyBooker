<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Flight;
use Illuminate\Support\Facades\Log;

class FlightUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Flight $flight)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
public function toMail($notifiable)
{
    try{
        return (new MailMessage)
        ->subject('Your Flight Has Been Updated')
        ->line('Flight Code: ' . $this->flight->flight_number)
        ->line('Airline: ' . $this->flight->airline->name)
        ->line('From: ' . $this->flight->origin->name)
        ->line('To: ' . $this->flight->destination->name)
        ->line('Departure Date: ' . $this->flight->departure_date)
        ->line('Departure Time: ' . $this->flight->departure_time)
        ->line('Arrival Time: ' . $this->flight->arrival_time)
        ->line('Duration: ' . $this->flight->duration);
    } catch (\Exception $e) {
        Log::error('SMTP ERROR: ' . $e->getMessage());
        throw $e;
    }
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
{
    return [
        'flight_id' => $this->flight->id,
        'code' => $this->flight->flight_number,
        'from' => $this->flight->origin->name,
        'to' => $this->flight->destination->name,
        'departure_time' => $this->flight->departure_time,
        'arrival_time' => $this->flight->arrival_time
    ];
}

}
