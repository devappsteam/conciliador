<?php

namespace App\Modules\ERP\Department\Policies;

use App\Modules\ERP\Department\Models\Department;

class DepartmentPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Department $department): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Department $department): bool
    {
        return true;
    }

    public function delete($user, Department $department): bool
    {
        return true;
    }

    public function restore($user, Department $department): bool
    {
        return true;
    }

    public function forceDelete($user, Department $department): bool
    {
        return true;
    }
}
