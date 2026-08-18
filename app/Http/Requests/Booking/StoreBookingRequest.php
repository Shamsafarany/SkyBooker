<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'flight_id' => 'required|exists:flights,id',
            'booking_reference' => 'nullable|string|max:255',
            'number_of_seats' => 'required|integer|min:1',
            'booking_date' => 'required|date',
            'total_price' => 'required|numeric|min:1',
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed', 'failed', 'refunded'])],
            'notes' => 'nullable|string|max:1000',
            'special_requests' => 'nullable|string|max:1000',
            //Passenger Validation
            'passengers' => 'required|array|min:1',
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.email' => 'required|email|max:255',
            'passengers.*.phone' => 'nullable|string|max:255',
            'passengers.*.date_of_birth' => 'nullable|date|before:today',
            'passengers.*.nationality' => 'required|string|max:255',
            'passengers.*.passport_number' => 'required|string|max:255',
            'passengers.*.id_number' => 'required|string|max:255',
            'passengers.*.seat_number' => 'nullable|string|max:10',
            'passengers.*.meal_preference' => ['nullable', Rule::in(['standard', 'vegetarian', 'vegan', 'gluten_free', 'kosher', 'halal', 'child_meal', 'none'])],
            'passengers.*.status' => ['nullable', Rule::in(['pending', 'confirmed', 'checked_in', 'boarded', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            // Booking
            'user_id.required' => 'Customer is required.',
            'user_id.exists' => 'Selected customer does not exist.',
            'flight_id.required' => 'Flight is required.',
            'flight_id.exists' => 'Selected flight does not exist.',
            'number_of_seats.required' => 'Number of seats is required.',
            'number_of_seats.min' => 'At least 1 seat required.',
            'booking_date.required' => 'Booking date is required.',
            'total_price.required' => 'Total price is required.',
            'total_price.min' => 'Price must be at least 1.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
            'special_requests.max' => 'Special requests cannot exceed 1000 characters.',
            
            // Passengers
            'passengers.required' => 'At least one passenger required.',
            'passengers.min' => 'At least one passenger required.',
            'passengers.*.first_name.required' => 'Passenger first name required.',
            'passengers.*.last_name.required' => 'Passenger last name required.',
            'passengers.*.email.required' => 'Passenger email required.',
            'passengers.*.email.email' => 'Invalid email format.',
            'passengers.*.nationality.required' => 'Nationality required.',
            'passengers.*.passport_number.required' => 'Passport number required.',
            'passengers.*.id_number.required' => 'ID number required.',
            'passengers.*.meal_preference.in' => 'Invalid meal preference.',
            'passengers.*.status.in' => 'Invalid passenger status.',
        ];
    }
}
