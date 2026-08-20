<?php

namespace App\Modules\Integrations\AcquirerTransaction\Providers;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;
use App\Modules\Integrations\AcquirerTransaction\Policies\AcquirerTransactionPolicy;
use App\Modules\Integrations\AcquirerTransaction\Repositories\Contracts\AcquirerTransactionRepositoryInterface;
use App\Modules\Integrations\AcquirerTransaction\Repositories\AcquirerTransactionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AcquirerTransactionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AcquirerTransactionRepositoryInterface::class, AcquirerTransactionRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(AcquirerTransaction::class, AcquirerTransactionPolicy::class);
    }
}
