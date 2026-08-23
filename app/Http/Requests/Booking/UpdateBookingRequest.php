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
        $this->merge([
            'booking_reference' => $this->sanitize($this->booking_reference),
            'notes' => $this->sanitize($this->notes),
            'special_requests' => $this->sanitize($this->special_requests),
            'status' => $this->sanitize($this->status),
            'number_of_seats' => (int) $this->number_of_seats,
            'total_price' => (float) $this->total_price,
        ]);
    }
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
