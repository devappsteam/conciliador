<?php

namespace App\Modules\Core\IAM\Repositories;

use App\Modules\Core\IAM\Models\Permission;
use App\Modules\Core\IAM\Repositories\Contracts\PermissionRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}
