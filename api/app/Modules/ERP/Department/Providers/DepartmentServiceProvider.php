<?php

namespace App\Modules\ERP\Department\Providers;

use App\Modules\ERP\Department\Models\Department;
use App\Modules\ERP\Department\Policies\DepartmentPolicy;
use App\Modules\ERP\Department\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Modules\ERP\Department\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DepartmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Department::class, DepartmentPolicy::class);
    }
}
