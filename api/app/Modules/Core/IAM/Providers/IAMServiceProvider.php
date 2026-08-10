<?php

namespace App\Modules\Core\IAM\Providers;

use App\Modules\Core\IAM\Repositories\Contracts\RoleRepositoryInterface;
use App\Modules\Core\IAM\Repositories\RoleRepository;
use App\Modules\Core\IAM\Repositories\Contracts\PermissionRepositoryInterface;
use App\Modules\Core\IAM\Repositories\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot(): void
    {

    }
}
