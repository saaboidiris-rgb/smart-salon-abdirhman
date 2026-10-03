<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canAccessAdmin();
    }

    public function rules(): array
    {
        $isCreating = $this->isMethod('post');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:600'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:active,inactive'],
            'image' => [$isCreating ? 'nullable' : 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'employees' => ['nullable', 'array'],
            'employees.*' => ['exists:employees,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes' => 'The image must be a JPG or PNG file.',
            'image.max' => 'The image may not be larger than 2MB.',
        ];
    }
}
