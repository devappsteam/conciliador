<?php

namespace App\Modules\Core\Company\Providers;

use App\Modules\Core\Company\Models\Company;
use App\Modules\Core\Company\Policies\CompanyPolicy;
use App\Modules\Core\Company\Repositories\Contracts\CompanyRepositoryInterface;
use App\Modules\Core\Company\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Company::class, CompanyPolicy::class);
    }
}
