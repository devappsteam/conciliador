<?php

namespace App\Modules\ERP\Department\Repositories;

use App\Modules\ERP\Department\Models\Department;
use App\Modules\ERP\Department\Repositories\Contracts\DepartmentRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->where('is_active', true)->orderBy('name')->get();
    }
}
