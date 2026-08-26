<?php

namespace App\Http\Requests\Airport;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAirportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $airport = $this->route('airport');
        return [
            'name' => 'sometimes|string|max:255',
            'code' => [
            'sometimes',
            'string',
            'size:3',
            Rule::unique('airports', 'code')->ignore($airport->id), 
            ],
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'terminals' => 'sometimes|integer|min:1',
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'maintenance', 'closed'])],
        ];
    }

    public function messages(): array
    {
        return [
            'code.size' => 'The airport code must be exactly 3 characters long.',
            'code.unique' => 'This airport code is already in use.',
            'terminals.integer' => 'The number of terminals must be a valid number.',
            'terminals.min' => 'The airport must have at least 1 terminal.',
            // Status validation messages
            'status.in' => 'Invalid status selected. Please select from: active, inactive, maintenance, or closed.',
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];
        if ($this->has('name')) {
            $data['name'] = $this->sanitize($this->name);
        }

        if ($this->has('code')) {
            $data['code'] = $this->sanitize($this->code);
        }

        if ($this->has('city')) {
            $data['city'] = $this->sanitize($this->city);
        }

        if ($this->has('country')) {
            $data['country'] = $this->sanitize($this->country);
        }

        if ($this->has('terminals')) {
            $data['terminals'] = $this->sanitize($this->terminals);
        }

        if ($this->has('status')) {
            $data['status'] = $this->sanitize($this->status);
        }

        $this->merge($data);
    }
    
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
