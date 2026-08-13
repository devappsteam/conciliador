<?php

namespace App\Modules\Integrations\Bank\Policies;

use App\Modules\Integrations\Bank\Models\Bank;

class BankPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Bank $bank): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Bank $bank): bool
    {
        return true;
    }

    public function delete($user, Bank $bank): bool
    {
        return true;
    }

    public function restore($user, Bank $bank): bool
    {
        return true;
    }

    public function forceDelete($user, Bank $bank): bool
    {
        return true;
    }
}
