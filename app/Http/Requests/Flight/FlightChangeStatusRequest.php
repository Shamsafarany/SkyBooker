<?php

namespace App\Http\Requests\Flight;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as IlluminateValidationRule;

class FlightChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                IlluminateValidationRule::in(['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed']),
            ],
        ];
    }
}
