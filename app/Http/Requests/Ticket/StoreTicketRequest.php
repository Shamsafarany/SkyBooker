<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Traits\SanitizesInput;

class StoreTicketRequest extends FormRequest
{
    use SanitizesInput;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passenger_id' => [
                'required',
                'exists:passengers,id',
                Rule::unique('tickets', 'passenger_id')
            ],
            'ticket_number' => 'required|string|max:255|unique:tickets,ticket_number',
            'seat_number' => 'nullable|string|max:10',
            'class' => ['required', Rule::in(['economy', 'premium_economy', 'business', 'first'])],
            'meal_preference' => ['nullable', Rule::in(['standard', 'full_meal', 'sandwitch', 'child_meal', 'none'])],
            'notes' => 'nullable|string|max:1000',
            'issued_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_number.required' => 'Ticket number is required.',
            'passenger_id.required' => 'Passenger ID is required.',
            'passenger_id.exists' => 'Selected passenger does not exist.',
            'passenger_id.unique' => 'This passenger already has a ticket.',
            'class.required' => 'Ticket class is required.',
            'class.in' => 'Invalid class selected.',
            'meal_preference.in' => 'Invalid meal preference.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->sanitizeAll([
            'passenger_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'seat_number',
            'class',
            'meal_preference',
        ]);
    }

}
