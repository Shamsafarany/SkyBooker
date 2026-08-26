<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\SanitizesInput;

class UpdateBookingRequest extends FormRequest
{
    use SanitizesInput;
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
        ];
    }
    protected function prepareForValidation()
    {
        $this->sanitizeOnly([
            'booking_reference',
            'notes',
            'special_requests',
            'status',
            'number_of_seats',
            'total_price',
        ]);
    }
}
