<?php

namespace App\Http\Requests\Flight;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flight_number' => [
                'sometimes',
                'string',
                'max:255',
                ],
            'airline_id' => 'sometimes|exists:airlines,id',
            'origin_airport_id' => 'sometimes|exists:airports,id|different:destination_airport_id',
            'destination_airport_id' => 'sometimes|exists:airports,id|different:origin_airport_id',
            'airplane_id' => 'sometimes|exists:airplanes,id',
            'departure_date' => 'sometimes|date',
            'departure_time' => 'sometimes',
            'arrival_date' => 'sometimes|date|after_or_equal:departure_date',
            'arrival_time' => 'sometimes',
            'duration' => 'sometimes|string|max:50',
            'price' => 'sometimes|numeric|min:0|max:99999.99',
            'total_seats' => 'sometimes|integer|min:1|max:1000',
            'status' => ['sometimes', Rule::in(['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed'])],
            'booking_deadline' => 'nullable|date|before:departure_date',
        ];
    }
    public function messages(): array
    {
        return [
            'flight_number.unique' => 'This flight number already exists.',
            'departure_date.after_or_equal' => 'Departure date cannot be in the past.',
            'arrival_date.after' => 'Arrival date must be after the departure date.',
            'origin_airport_id.different' => 'Origin and destination must be different.',
            'price.min' => 'Price must be at least 0.',
            'price.max' => 'Price cannot exceed 99,999.99.',
            'total_seats.min' => 'Must have at least 1 seat.',
            'total_seats.max' => 'Cannot exceed 1000 seats.',
            'status.in' => 'Invalid status selected.',
            'booking_deadline.before' => 'Booking deadline must be before departure date.',
        ];
    }
}
