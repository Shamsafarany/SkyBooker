<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\SanitizesInput;

class StoreBookingRequest extends FormRequest
{
    use SanitizesInput;
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
        ];
    }
    protected function prepareForValidation()
    {
        $this->sanitizeAll([
            'user_id',
            'flight_id',
            'number_of_seats',
            'total_price',
            'status',
            'notes',
            'special_requests',
        ]);
    }
}
