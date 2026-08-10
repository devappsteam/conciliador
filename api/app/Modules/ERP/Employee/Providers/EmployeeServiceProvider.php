<?php

namespace App\Modules\ERP\Employee\Providers;

use App\Modules\ERP\Employee\Models\Employee;
use App\Modules\ERP\Employee\Policies\EmployeePolicy;
use App\Modules\ERP\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Modules\ERP\Employee\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class EmployeeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Employee::class, EmployeePolicy::class);
    }
}
