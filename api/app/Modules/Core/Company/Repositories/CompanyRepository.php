<?php

namespace App\Modules\Core\Company\Repositories;

use App\Modules\Core\Company\Enums\Status;
use Illuminate\Database\Eloquent\Collection;

use App\Modules\Core\Company\Models\Company;
use App\Modules\Core\Company\Repositories\Contracts\CompanyRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->orderBy('corporate_name', 'ASC')->where('status', Status::ACTIVE)->get();
    }

    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = $this->query($relations);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('corporate_name', 'like', "%{$search}%")
                    ->orWhere('trade_name', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('corporate_name', 'ASC')->paginate($perPage);
    }
}
