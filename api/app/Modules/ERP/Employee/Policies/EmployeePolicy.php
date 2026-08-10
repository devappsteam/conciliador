<?php

namespace App\Modules\ERP\Employee\Policies;

use App\Modules\ERP\Employee\Models\Employee;

class EmployeePolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Employee $employee): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Employee $employee): bool
    {
        return true;
    }

    public function delete($user, Employee $employee): bool
    {
        return true;
    }

    public function restore($user, Employee $employee): bool
    {
        return true;
    }

    public function forceDelete($user, Employee $employee): bool
    {
        return true;
    }
}
