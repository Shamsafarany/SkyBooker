<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'flight_id' => 'sometimes|exists:flights,id',
            'booking_reference' => 'nullable|string|max:255',
            'number_of_seats' => 'sometimes|integer|min:1',
            'booking_date' => 'sometimes|date',
            'total_price' => 'sometimes|numeric|min:1',
            'status' => ['sometimes', Rule::in(['pending', 'confirmed', 'cancelled', 'completed', 'failed', 'refunded'])],
            'notes' => 'nullable|string|max:1000',
            'special_requests' => 'nullable|string|max:1000',
            
            // Passenger Validation
            'passengers' => 'sometimes|array|min:1',
            'passengers.*.id' => 'sometimes|exists:passengers,id',
            'passengers.*.first_name' => 'sometimes_with:passengers|string|max:255',
            'passengers.*.last_name' => 'sometimes_with:passengers|string|max:255',
            'passengers.*.email' => 'sometimes_with:passengers|email|max:255',
            'passengers.*.phone' => 'nullable|string|max:255',
            'passengers.*.date_of_birth' => 'nullable|date|before:today',
            'passengers.*.nationality' => 'nullable|string|max:255',
            'passengers.*.passport_number' => 'nullable|string|max:255',
            'passengers.*.id_number' => 'nullable|string|max:255',
            'passengers.*.seat_number' => 'nullable|string|max:10',
            'passengers.*.meal_preference' => ['nullable', Rule::in(['standard', 'vegetarian', 'vegan', 'gluten_free', 'kosher', 'halal', 'child_meal', 'none'])],
            'passengers.*.status' => ['nullable', Rule::in(['pending', 'confirmed', 'checked_in', 'boarded', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            // Booking
            'user_id.exists' => 'Selected customer does not exist.',
            'flight_id.exists' => 'Selected flight does not exist.',
            'total_price.min' => 'Price must be at least 1.',
            'status.in' => 'Invalid status.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
            'special_requests.max' => 'Special requests cannot exceed 1000 characters.',
            
            // Passengers
            'passengers.*.email.email' => 'Invalid email format.',
            'passengers.*.meal_preference.in' => 'Invalid meal preference.',
            'passengers.*.status.in' => 'Invalid passenger status.',
        ];
    }
}
