<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canAccessAdmin();
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employeeId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'working_days' => ['required', 'array', 'min:1'],
            'working_days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'working_hours_start' => ['required', 'date_format:H:i'],
            'working_hours_end' => ['required', 'date_format:H:i', 'after:working_hours_start'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'working_hours_end.after'  => 'End time must be after the start time.',
            'working_days.min'         => 'Select at least one working day.',
            'photo.max'                => 'Photo must not exceed 5 MB. Please resize the image before uploading.',
            'photo.mimes'              => 'Photo must be a JPG, PNG or WebP image.',
            'photo.image'              => 'The uploaded file must be an image.',
        ];
    }
}
