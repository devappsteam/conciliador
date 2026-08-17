<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Policies;

use App\Modules\Reconciliation\AcquirerConfig\Models\AcquirerConfig;

class AcquirerConfigPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, AcquirerConfig $acquirerConfig): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, AcquirerConfig $acquirerConfig): bool
    {
        return true;
    }

    public function delete($user, AcquirerConfig $acquirerConfig): bool
    {
        return true;
    }

    public function restore($user, AcquirerConfig $acquirerConfig): bool
    {
        return true;
    }

    public function forceDelete($user, AcquirerConfig $acquirerConfig): bool
    {
        return true;
    }
}
