<?php

namespace App\Modules\Core\IAM\Repositories;

use App\Modules\Core\IAM\Models\Role;
use App\Modules\Core\IAM\Repositories\Contracts\RoleRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function update(Model $model, array $data): bool
    {
        return DB::transaction(function () use ($model, $data) {
            $updated = $model->update($data);

            if (!$updated) {
                return false;
            }

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $model->permissions()->sync($data['permissions']);
            }

            return true;
        });
    }
}
