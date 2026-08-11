<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Solo el administrador puede crear servicios.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'duration' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'duration.required' => 'La duración es obligatoria.',
            'duration.min' => 'La duración debe ser mayor que 0.',
            'price.required' => 'El precio es obligatorio.',
            'price.min' => 'El precio debe ser mayor que 0.',
        ];
    }
}
