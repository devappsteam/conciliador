<?php

namespace App\Modules\Core\User\Policies;

use App\Modules\Core\User\Models\User;

class UserPolicy
{
    /** Slugs de roles com privilégios administrativos sobre outros usuários */
    protected const ADMIN_SLUGS = ['master', 'admin'];

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $this->isAdmin($user);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id !== $model->id && $this->isAdmin($user);
    }

    public function restore(User $user, User $model): bool
    {
        return $this->isAdmin($user);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $this->isAdmin($user);
    }

    protected function isAdmin(User $user): bool
    {
        return $user->roles()->whereIn('slug', self::ADMIN_SLUGS)->exists();
    }
}
