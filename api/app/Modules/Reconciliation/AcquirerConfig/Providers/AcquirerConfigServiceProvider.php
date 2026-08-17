<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Providers;

use App\Modules\Reconciliation\AcquirerConfig\Repositories\CompanyAcquirerRepository;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts\CompanyAcquirerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AcquirerConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyAcquirerRepositoryInterface::class, CompanyAcquirerRepository::class);
    }

    public function boot(): void
    {

    }
}
