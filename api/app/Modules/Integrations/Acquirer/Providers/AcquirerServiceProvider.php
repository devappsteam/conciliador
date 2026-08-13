<?php

namespace App\Modules\Integrations\Acquirer\Providers;

use App\Modules\Integrations\Acquirer\Models\Acquirer;
use App\Modules\Integrations\Acquirer\Policies\AcquirerPolicy;
use App\Modules\Integrations\Acquirer\Repositories\Contracts\AcquirerRepositoryInterface;
use App\Modules\Integrations\Acquirer\Repositories\AcquirerRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AcquirerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AcquirerRepositoryInterface::class, AcquirerRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Acquirer::class, AcquirerPolicy::class);
    }
}
