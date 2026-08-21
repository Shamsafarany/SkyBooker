<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ticket = $this->route('ticket');
        
        return [
            'passenger_id' => 'sometimes|exists:passengers,id',
            'ticket_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('tickets', 'ticket_number')->ignore($ticket->id),
            ],
            'seat_number' => 'nullable|string|max:10',
            'class' => ['sometimes', Rule::in(['economy', 'premium_economy', 'business', 'first'])],
            'meal_preference' => ['nullable', Rule::in(['standard', 'full_meal', 'sandwitch', 'child_meal', 'none'])],
            'status' => ['sometimes', Rule::in(['issued', 'used', 'cancelled', 'expired'])],
            'notes' => 'nullable|string|max:1000',
            'issued_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'passenger_id.exists' => 'Selected passenger does not exist.',
            'ticket_number.unique' => 'This ticket number already exists.',
            'class.in' => 'Invalid class selected.',
            'status.in' => 'Invalid status selected.',
            'meal_preference.in' => 'Invalid meal preference.',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
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
