<?php

namespace App\Modules\ERP\Position\Policies;

use App\Modules\ERP\Position\Models\Position;

class PositionPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Position $position): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Position $position): bool
    {
        return true;
    }

    public function delete($user, Position $position): bool
    {
        return true;
    }

    public function restore($user, Position $position): bool
    {
        return true;
    }

    public function forceDelete($user, Position $position): bool
    {
        return true;
    }
}
