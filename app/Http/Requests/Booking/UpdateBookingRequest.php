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
    protected function prepareForValidation()
    {
        $this->merge([
            'booking_reference' => $this->sanitize($this->booking_reference),
            'notes' => $this->sanitize($this->notes),
            'special_requests' => $this->sanitize($this->special_requests),
            'status' => $this->sanitize($this->status),
            'number_of_seats' => (int) $this->number_of_seats,
            'total_price' => (float) $this->total_price,
        ]);

        // Sanitize passengers
        if ($this->has('passengers')) {
            $passengers = collect($this->passengers)->map(function ($passenger) {
                return [
                    'first_name' => $this->sanitize($passenger['first_name'] ?? ''),
                    'last_name' => $this->sanitize($passenger['last_name'] ?? ''),
                    'email' => $this->sanitize($passenger['email'] ?? ''),
                    'phone' => $this->sanitize($passenger['phone'] ?? ''),
                    'date_of_birth' => $passenger['date_of_birth'] ?? null,
                    'nationality' => $this->sanitize($passenger['nationality'] ?? ''),
                    'passport_number' => $this->sanitize($passenger['passport_number'] ?? ''),
                    'id_number' => $this->sanitize($passenger['id_number'] ?? ''),
                    'seat_number' => $this->sanitize($passenger['seat_number'] ?? ''),
                    'meal_preference' => $this->sanitize($passenger['meal_preference'] ?? 'standard'),
                    'status' => $this->sanitize($passenger['status'] ?? 'pending'),
                ];
            })->toArray();
            
            $this->merge(['passengers' => $passengers]);
        }
    }
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
