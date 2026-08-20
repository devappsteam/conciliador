<?php

namespace App\Modules\Integrations\Brand\Policies;

use App\Modules\Integrations\Brand\Models\Brand;

class BrandPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Brand $brand): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Brand $brand): bool
    {
        return true;
    }

    public function delete($user, Brand $brand): bool
    {
        return true;
    }

    public function restore($user, Brand $brand): bool
    {
        return true;
    }

    public function forceDelete($user, Brand $brand): bool
    {
        return true;
    }
}
