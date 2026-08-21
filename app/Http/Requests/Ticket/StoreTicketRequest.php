<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Ticket;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passenger_id' => 'required|exists:passengers,id',
            'ticket_number' => 'nullable|string|max:255|unique:tickets,ticket_number',
            'seat_number' => 'nullable|string|max:10',
            'class' => ['required', Rule::in(['economy', 'premium_economy', 'business', 'first'])],
            'meal_preference' => ['nullable', Rule::in(['standard', 'full_meal', 'sandwitch', 'child_meal', 'none'])],
            'status' => ['required', Rule::in(['issued', 'used', 'cancelled', 'expired'])],
            'notes' => 'nullable|string|max:1000',
            'issued_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'passenger_id.required' => 'Passenger ID is required.',
            'passenger_id.exists' => 'Selected passenger does not exist.',
            'class.required' => 'Ticket class is required.',
            'class.in' => 'Invalid class selected.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'meal_preference.in' => 'Invalid meal preference.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ticket_number' => Ticket::generateTicketNumber(),
            'issued_at' => now(),
            'seat_number' => $this->sanitize($this->seat_number),
            'meal_preference' => $this->sanitize($this->meal_preference),
            'status' => $this->sanitize($this->status),
            'notes' => $this->sanitize($this->notes),
        ]);
    }
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }

}
