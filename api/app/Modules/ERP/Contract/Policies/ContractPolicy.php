<?php

namespace App\Modules\ERP\Contract\Policies;

use App\Modules\ERP\Contract\Models\Contract;

class ContractPolicy
{
    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user, Contract $contract): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user, Contract $contract): bool
    {
        return true;
    }

    public function delete($user, Contract $contract): bool
    {
        return true;
    }

    public function restore($user, Contract $contract): bool
    {
        return true;
    }

    public function forceDelete($user, Contract $contract): bool
    {
        return true;
    }
}
