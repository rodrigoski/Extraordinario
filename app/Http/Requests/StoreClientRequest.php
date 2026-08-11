<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    /**
     * Solo el administrador puede crear clientes.
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
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')->whereNull('deleted_at')],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\d\s\-()+.\/]+$/', function ($attribute, $value, $fail) {
                if (strlen(preg_replace('/\D/', '', $value)) !== 10) {
                    $fail('El teléfono debe contener exactamente 10 dígitos.');
                }
            }],
            'address' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Introduce un correo válido.',
            'email.unique' => 'Ya existe un cliente con ese correo.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.regex' => 'El teléfono solo puede contener dígitos y caracteres de formato (+, -, (), espacios).',
        ];
    }
}
