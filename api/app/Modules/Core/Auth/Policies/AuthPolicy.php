<?php

namespace App\Modules\Core\Auth\Policies;

use App\Modules\Core\Auth\Models\Auth;

class AuthPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Auth $auth): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Auth $auth): bool
    {
        return true;
    }

    public function delete($user, Auth $auth): bool
    {
        return true;
    }

    public function restore($user, Auth $auth): bool
    {
        return true;
    }

    public function forceDelete($user, Auth $auth): bool
    {
        return true;
    }
}
