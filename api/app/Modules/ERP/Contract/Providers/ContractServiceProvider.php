<?php

namespace App\Modules\ERP\Contract\Providers;

use App\Modules\ERP\Contract\Models\Contract;
use App\Modules\ERP\Contract\Policies\ContractPolicy;
use App\Modules\ERP\Contract\Repositories\Contracts\ContractRepositoryInterface;
use App\Modules\ERP\Contract\Repositories\ContractRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ContractServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContractRepositoryInterface::class, ContractRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Contract::class, ContractPolicy::class);
    }
}
