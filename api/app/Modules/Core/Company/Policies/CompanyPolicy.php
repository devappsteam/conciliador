<?php

namespace App\Modules\Core\Company\Policies;

use App\Modules\Core\Company\Models\Company;

class CompanyPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Company $company): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Company $company): bool
    {
        return true;
    }

    public function delete($user, Company $company): bool
    {
        return true;
    }

    public function restore($user, Company $company): bool
    {
        return true;
    }

    public function forceDelete($user, Company $company): bool
    {
        return true;
    }
}
