<?php

namespace App\Modules\Integrations\AcquirerTransaction\Repositories;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;
use App\Modules\Integrations\AcquirerTransaction\Repositories\Contracts\AcquirerTransactionRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class AcquirerTransactionRepository extends BaseRepository implements AcquirerTransactionRepositoryInterface
{
    public function __construct(AcquirerTransaction $model)
    {
        parent::__construct($model);
    }

    public function findByNsu(string $companyId, string $establishmentCode, string $nsu): ?AcquirerTransaction
    {
        return $this->model
            ->where('company_id', $companyId)
            ->where('establishment_code', $establishmentCode)
            ->where('acquirer_nsu', $nsu)
            ->first();
    }

    public function updateReconciliationStatus(string $id, string $status): bool
    {
        return $this->model
            ->where('id', $id)
            ->update(['reconciliation_status' => $status]);
    }
}
