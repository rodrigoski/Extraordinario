<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Admin y staff pueden ver el listado de citas.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    /**
     * El cliente solo puede ver sus propias citas.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if (in_array($user->role, ['admin', 'staff'], true)) {
            return true;
        }

        return $user->client?->id === $appointment->client_id;
    }

    /**
     * Admin, staff y el propio cliente pueden crear citas (el cliente solo
     * mediante el flujo de reserva, que fuerza su client_id).
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'cliente'], true);
    }

    /**
     * Admin puede editar cualquier cita; el staff solo las suyas.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'staff') {
            return $appointment->employee_id === $user->employee?->id;
        }

        return false;
    }

    /**
     * Solo el admin puede eliminar citas.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->role === 'admin';
    }
}
