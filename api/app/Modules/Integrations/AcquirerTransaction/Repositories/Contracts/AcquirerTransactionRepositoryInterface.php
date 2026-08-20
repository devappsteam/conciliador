<?php

namespace App\Modules\Integrations\AcquirerTransaction\Repositories\Contracts;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;
use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;

interface AcquirerTransactionRepositoryInterface extends RepositoryInterface
{
    public function findByNsu(string $companyId, string $establishmentCode, string $nsu): ?AcquirerTransaction;
    public function updateReconciliationStatus(string $id, string $status): bool;
}
