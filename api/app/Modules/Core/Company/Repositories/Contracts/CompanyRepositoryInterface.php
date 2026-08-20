<?php

namespace App\Modules\Core\Company\Repositories\Contracts;

use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface extends RepositoryInterface
{
    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator;
}
