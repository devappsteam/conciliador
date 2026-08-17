<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts;

use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;

interface CompanyAcquirerRepositoryInterface
{
    public function findActiveByCompanyAndAcquirer(string $companyId, string $acquirerId): ?CompanyAcquirer;

    /**
     * Busca a configuração da adquirente com as taxas válidas para uma data específica.
     */
    public function getWithRatesAndAnticipation(string $companyAcquirerId): ?CompanyAcquirer;
}
