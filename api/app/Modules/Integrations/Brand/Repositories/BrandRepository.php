<?php

namespace App\Modules\Integrations\Brand\Repositories;

use App\Modules\Integrations\Brand\Models\Brand;
use App\Modules\Integrations\Brand\Repositories\Contracts\BrandRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->orderBy('name')->get();
    }

    public function paginateWithFilters(array $filters, int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = $this->query($relations);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool) $filters['status']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
