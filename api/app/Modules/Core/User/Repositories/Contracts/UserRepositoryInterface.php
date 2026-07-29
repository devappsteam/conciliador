<?php

namespace App\Modules\Core\User\Repositories\Contracts;

use DevApps\LaravelModulesKit\Contracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function updateLastLogin(int $userId): void;
}
