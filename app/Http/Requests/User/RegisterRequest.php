<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['nullable', Rule::in(['admin', 'passenger'])],
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'role.in' => 'Invalid role selected.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('username') && $this->has('first_name') && $this->has('last_name')) {
            $this->merge([
                'username' => strtolower($this->first_name . $this->last_name),
            ]);
        }

        if (!$this->has('role')) {
            $this->merge([
                'role' => 'passenger',
            ]);
        }

        $this->merge([
            'first_name' => $this->sanitize($this->first_name),
            'last_name' => $this->sanitize($this->last_name),
            'email' => $this->sanitize($this->email),
            'username' => $this->sanitize($this->username),
            'phone' => $this->sanitize($this->phone),
            'date_of_birth' => $this->sanitize($this->date_of_birth),
        ]);
    }

    private function sanitize($value): mixed
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
