<?php

namespace App\Http\Requests\Airport;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAirportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:airports,code',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'terminals' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'closed'])],
        ];
    }
    public function messages(): array
    {
        return [

            'name.required' => 'The airport name is required.',
            'code.required' => 'The airport code is required.',
            'code.size' => 'The airport code must be exactly 3 characters long.',
            'code.unique' => 'This airport code is already in use.',
            'city.required' => 'The city name is required.',
            'country.required' => 'The country name is required.',
            'terminals.required' => 'The number of terminals is required.',
            'terminals.integer' => 'The number of terminals must be a valid number.',
            'terminals.min' => 'The airport must have at least 1 terminal.',
            // Status validation messages
            'status.required' => 'The airport status is required.',
            'status.in' => 'Invalid status selected. Please select from: active, inactive, maintenance, or closed.',
        ];
    }
}
