<?php

namespace App\Http\Requests\Passenger;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\SanitizesInput;
class StorePassengerRequest extends FormRequest
{
    use SanitizesInput;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Required fields
            'booking_id' => 'required|exists:bookings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nationality' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255|unique:passengers,passport_number',
            'id_number' => 'required|string|max:255|unique:passengers,id_number',
            
            // Optional fields
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
            // Required messages
            'booking_id.required' => 'Booking ID is required.',
            'booking_id.exists' => 'Selected booking does not exist.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'nationality.required' => 'Nationality is required.',
            'passport_number.required' => 'Passport number is required.',
            'id_number.required' => 'ID number is required.',
            'passport_number.unique' => 'This passport number is already registered.',
            'id_number.unique' => 'This ID number is already registered.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'meal_preference.in' => 'Invalid meal preference.',
            'status.in' => 'Invalid status.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->sanitizeAll([
            'booking_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'nationality',
            'passport_number',
            'id_number',
            'seat_number',
            'meal_preference',
            'status',
        ]);
    }
    
}
