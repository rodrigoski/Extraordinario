<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Admin y staff pueden consultar clientes.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    public function view(User $user, Client $client): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    /**
     * Solo el admin puede crear, editar o eliminar clientes.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Client $client): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Client $client): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
