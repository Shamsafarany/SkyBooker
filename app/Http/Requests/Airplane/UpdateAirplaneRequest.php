<?php

namespace App\Http\Requests\Airplane;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAirplaneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $airplane = $this->route('airplane');
        return [
            'model' => 'sometimes|string|max:255',
            'manufacturer' => 'sometimes|string|max:255',
            'registration' => [
            'sometimes',
            'string',
            'max:255',
            Rule::unique('airplanes', 'registration')->ignore($airplane->id), 
            ],
            'capacity' => 'sometimes|integer|min:1|max:1000',
            'year' => 'sometimes|integer|min:1950|max:' . date('Y'),
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'maintenance', 'retired'])],
        ];
    }

    public function messages(): array
    {
        return [
            'registration.unique' => 'This registration number is already in use.',
            'capacity.min' => 'Capacity must be at least 1.',
            'capacity.max' => 'Capacity cannot exceed 1000.',
            'year.min' => 'Year must be 1950 or later.',
            'year.max' => 'Year cannot be in the future.',
            'status.in' => 'Invalid status selected.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'model' => $this->sanitize($this->model),
            'manufacturer' => $this->sanitize($this->manufacturer),
            'registration' => $this->sanitize($this->registration),
        ]);
    }
    private function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
