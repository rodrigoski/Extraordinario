<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Administrador y staff pueden actualizar citas (el staff solo las suyas,
     * verificado además por la Policy).
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff'], true);
    }

    /**
     * Calculamos la hora de finalización a partir de la duración
     * del servicio antes de validar.
     */
    protected function prepareForValidation(): void
    {
        $service = Service::find($this->input('service_id'));

        if ($service && $this->filled('start_time') && $this->filled('appointment_date')) {
            $start = Carbon::parse($this->input('appointment_date').' '.$this->input('start_time'));

            $this->merge([
                'start_time' => $start->format('H:i:s'),
                'end_time' => $start->copy()->addMinutes((int) $service->duration)->format('H:i:s'),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'El cliente es obligatorio.',
            'employee_id.required' => 'El empleado es obligatorio.',
            'service_id.required' => 'El servicio es obligatorio.',
            'appointment_date.required' => 'La fecha es obligatoria.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
        ];
    }

    /**
     * Regla personalizada: el empleado no puede tener dos citas que se solapen,
     * ignorando la cita que se está editando.
     */
    public function after(): Closure
    {
        return function (Validator $validator) {
            if (! $this->filled(['employee_id', 'appointment_date', 'start_time', 'end_time'])) {
                return;
            }

            $conflict = Appointment::query()
                ->where('employee_id', $this->input('employee_id'))
                ->whereDate('appointment_date', $this->input('appointment_date'))
                ->where('id', '!=', $this->route('appointment')->id)
                ->whereNotIn('status', ['cancelled'])
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->input('start_time'), $this->input('end_time')])
                        ->orWhereBetween('end_time', [$this->input('start_time'), $this->input('end_time')])
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('start_time', '<', $this->input('start_time'))
                                ->where('end_time', '>', $this->input('end_time'));
                        });
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add('start_time', 'El empleado ya tiene una cita en ese horario.');
            }
        };
    }
}
