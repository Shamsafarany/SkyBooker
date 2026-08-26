<?php

namespace App\Http\Requests\Flight;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\SanitizesInput;

class StoreFlightRequest extends FormRequest
{
    use SanitizesInput;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flight_number' => 'required|string|max:255|unique:flights,flight_number',
            'airline_id' => 'required|exists:airlines,id',
            'origin_airport_id' => 'required|exists:airports,id|different:destination_airport_id',
            'destination_airport_id' => 'required|exists:airports,id|different:origin_airport_id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'arrival_time' => 'required',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0|max:99999.99',
            'total_seats' => 'required|integer|min:1|max:1000',
            'status' => ['required', Rule::in(['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed'])],
            'booking_deadline' => 'nullable|date|before:departure_date',
        ];
    }

    public function messages(): array
    {
        return [
            'flight_number.required' => 'Flight number is required.',
            'flight_number.unique' => 'This flight number already exists.',
            'airline_id.required' => 'Please select an airline.',
            'origin_airport_id.required' => 'Please select an origin airport.',
            'destination_airport_id.required' => 'Please select a destination airport.',
            'airplane_id.required' => 'Please select an airplane.',
            'departure_date.required' => 'Departure date is required.',
            'departure_date.after_or_equal' => 'Departure date cannot be in the past. Please select today or a future date.',
            'arrival_date.required' => 'Arrival date is required.',
            'arrival_date.after' => 'Arrival date must be after the departure date.',
            'price.required' => 'Price is required.',
            'total_seats.required' => 'Total seats is required.',
            'status.required' => 'Status is required.',
            'origin_airport_id.different' => 'Origin and destination must be different.',
            'price.min' => 'Price must be at least 0.',
            'total_seats.min' => 'Must have at least 1 seat.',
            'total_seats.max' => 'Cannot exceed 1000 seats.',
            'status.in' => 'Invalid status selected.',
            'booking_deadline.before' => 'Booking deadline must be before departure date.',
        ];
    }
    protected function prepareForValidation()
    {
        $this->sanitizeAll([
            'flight_number',
            'airline_id',
            'origin_airport_id',
            'destination_airport_id',
            'airplane_id',
            'departure_date',
            'departure_time',
            'arrival_date',
            'arrival_time',
            'duration',
            'price',
            'total_seats',
            'status',
            'booking_deadline',
        ]);
    }
}
