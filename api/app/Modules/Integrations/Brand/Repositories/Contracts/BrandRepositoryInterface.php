<?php

namespace App\Modules\Integrations\Brand\Repositories\Contracts;

use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface BrandRepositoryInterface extends RepositoryInterface
{
    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator;
}
