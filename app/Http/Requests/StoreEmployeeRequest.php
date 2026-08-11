<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Solo el administrador puede crear empleados.
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
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'position' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Selecciona un usuario para el empleado.',
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'phone.required' => 'El teléfono es obligatorio.',
            'position.required' => 'El cargo es obligatorio.',
        ];
    }
}
