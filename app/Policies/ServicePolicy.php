<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    /**
     * Admin y staff pueden consultar servicios.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    public function view(User $user, Service $service): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    /**
     * Solo el admin puede crear, editar o eliminar servicios.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Service $service): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->role === 'admin';
    }
}
