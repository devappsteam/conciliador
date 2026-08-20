<?php

namespace App\Modules\Integrations\Bank\Repositories\Contracts;

use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface BankRepositoryInterface extends RepositoryInterface
{
    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator;
}
