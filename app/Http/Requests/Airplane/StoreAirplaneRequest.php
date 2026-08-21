<?php

namespace App\Http\Requests\Airplane;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAirplaneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        
        return [
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'registration' => 'required|string|max:255|unique:airplanes,registration',
            'capacity' => 'required|integer|min:1|max:1000',
            'year' => 'required|integer|min:1950|max:' . date('Y'),
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'retired'])],
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => 'The airplane model is required.',
            'manufacturer.required' => 'The manufacturer is required.',
            'registration.required' => 'The registration number is required.',
            'registration.unique' => 'This registration number is already in use.',
            'capacity.required' => 'The capacity is required.',
            'capacity.min' => 'Capacity must be at least 1.',
            'capacity.max' => 'Capacity cannot exceed 1000.',
            'year.required' => 'The manufacturing year is required.',
            'year.min' => 'Year must be 1950 or later.',
            'year.max' => 'Year cannot be in the future.',
            'status.required' => 'The status is required.',
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
