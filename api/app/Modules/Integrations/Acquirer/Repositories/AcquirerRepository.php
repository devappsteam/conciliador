<?php

namespace App\Modules\Integrations\Acquirer\Repositories;

use App\Modules\Integrations\Acquirer\Models\Acquirer;
use App\Modules\Integrations\Acquirer\Repositories\Contracts\AcquirerRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AcquirerRepository extends BaseRepository implements AcquirerRepositoryInterface
{
    public function __construct(Acquirer $model)
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
