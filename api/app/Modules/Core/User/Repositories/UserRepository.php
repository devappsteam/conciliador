<?php

namespace App\Modules\Core\User\Repositories;

use App\Modules\Core\User\Models\User;
use App\Modules\Core\User\Repositories\Contracts\UserRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
