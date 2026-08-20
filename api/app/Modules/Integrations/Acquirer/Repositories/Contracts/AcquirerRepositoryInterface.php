<?php

namespace App\Modules\Integrations\Acquirer\Repositories\Contracts;

use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface AcquirerRepositoryInterface extends RepositoryInterface
{
    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator;
}
