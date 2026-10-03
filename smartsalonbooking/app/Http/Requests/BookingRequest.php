<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'service_id' => ['required', 'exists:services,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            // "Future Date Only" - a booking can be made for today (if a
            // slot is still open later today) or any day after today.
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];

        // Guests booking for the first time also create an account, so we
        // collect and validate their details right here.
        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email'];
            $rules['phone'] = ['required', 'string', 'max:30'];
            $rules['password'] = ['required', 'confirmed', Password::min(8)->letters()->numbers()];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'appointment_date.after_or_equal' => 'Please choose today or a future date.',
            'email.unique' => 'That email is already registered - please log in first to book.',
        ];
    }
}
