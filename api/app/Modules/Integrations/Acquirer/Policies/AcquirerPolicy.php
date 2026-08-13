<?php

namespace App\Modules\Integrations\Acquirer\Policies;

use App\Modules\Integrations\Acquirer\Models\Acquirer;

class AcquirerPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Acquirer $acquirer): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Acquirer $acquirer): bool
    {
        return true;
    }

    public function delete($user, Acquirer $acquirer): bool
    {
        return true;
    }

    public function restore($user, Acquirer $acquirer): bool
    {
        return true;
    }

    public function forceDelete($user, Acquirer $acquirer): bool
    {
        return true;
    }
}
