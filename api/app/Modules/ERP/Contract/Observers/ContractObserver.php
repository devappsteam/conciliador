<?php

namespace App\Observers;

use App\Modules\ERP\Contract\Models\Contract;

class ContractObserver
{
    public function creating(Contract $contract): void
    {
        $lastCode = Contract::query()->whereNotNull('code')->orderByDesc('id')->value('code');
        $nextNumber = $lastCode ? ((int) $lastCode + 1) : 1;
        $contract->code = str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
    }
}
