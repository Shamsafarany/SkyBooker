<?php

namespace App\Http\Requests\Passenger;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePassengerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $passenger = $this->route('passenger');
        
        return [
            // Optional fields (only validate if present)
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'nationality' => 'sometimes|string|max:255',
            'passport_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('passengers', 'passport_number')->ignore($passenger->id),
            ],
            'id_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('passengers', 'id_number')->ignore($passenger->id),
            ],
            'phone' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'seat_number' => 'nullable|string|max:10',
            'meal_preference' => ['nullable', Rule::in(['standard','full_meal','sandwitch','child_meal','none'])],
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'checked_in', 'boarded', 'cancelled'])],
        ];
    }
    public function messages(): array
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'passport_number.unique' => 'This passport number is already registered.',
            'id_number.unique' => 'This ID number is already registered.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'meal_preference.in' => 'Invalid meal preference.',
            'status.in' => 'Invalid status.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'first_name' => $this->sanitize($this->first_name),
            'last_name' => $this->sanitize($this->last_name),
            'email' => $this->sanitize($this->email),
            'phone' => $this->sanitize($this->phone),
            'nationality' => $this->sanitize($this->nationality),
            'passport_number' => $this->sanitize($this->passport_number),
            'id_number' => $this->sanitize($this->id_number),
            'seat_number' => $this->sanitize($this->seat_number),
            'status' => $this->sanitize($this->status),
            'meal_preference' => $this->sanitize($this->meal_preference),
        ]);
    }
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
