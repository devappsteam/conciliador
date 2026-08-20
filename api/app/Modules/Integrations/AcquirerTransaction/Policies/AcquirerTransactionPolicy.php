<?php

namespace App\Modules\Integrations\AcquirerTransaction\Policies;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;

class AcquirerTransactionPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, AcquirerTransaction $acquirerTransaction): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, AcquirerTransaction $acquirerTransaction): bool
    {
        return true;
    }

    public function delete($user, AcquirerTransaction $acquirerTransaction): bool
    {
        return true;
    }

    public function restore($user, AcquirerTransaction $acquirerTransaction): bool
    {
        return true;
    }

    public function forceDelete($user, AcquirerTransaction $acquirerTransaction): bool
    {
        return true;
    }
}
