<?php

namespace App\Modules\ERP\Position\Repositories;

use App\Modules\ERP\Position\Models\Position;
use App\Modules\ERP\Position\Repositories\Contracts\PositionRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PositionRepository extends BaseRepository implements PositionRepositoryInterface
{
    public function __construct(Position $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->where('is_active', true)->orderBy('name')->get();
    }
}
