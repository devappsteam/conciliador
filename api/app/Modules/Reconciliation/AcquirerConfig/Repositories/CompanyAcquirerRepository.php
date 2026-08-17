<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Repositories;

use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts\CompanyAcquirerRepositoryInterface;

class CompanyAcquirerRepository implements CompanyAcquirerRepositoryInterface
{
    public function __construct(
        protected CompanyAcquirer $model
    ) {}

    public function findActiveByCompanyAndAcquirer(string $companyId, string $acquirerId): ?CompanyAcquirer
    {
        return $this->model
            ->where('company_id', $companyId)
            ->where('acquirer_id', $acquirerId)
            ->where('is_active', true)
            ->first();
    }

    public function getWithRatesAndAnticipation(string $companyAcquirerId): ?CompanyAcquirer
    {
        return $this->model
            ->with(['rates', 'anticipationConfig'])
            ->find($companyAcquirerId);
    }
}
