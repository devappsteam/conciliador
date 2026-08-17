<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Providers;

use App\Modules\Reconciliation\AcquirerConfig\Models\AcquirerConfig;
use App\Modules\Reconciliation\AcquirerConfig\Policies\AcquirerConfigPolicy;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts\AcquirerConfigRepositoryInterface;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\AcquirerConfigRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AcquirerConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AcquirerConfigRepositoryInterface::class, AcquirerConfigRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(AcquirerConfig::class, AcquirerConfigPolicy::class);
    }
}
