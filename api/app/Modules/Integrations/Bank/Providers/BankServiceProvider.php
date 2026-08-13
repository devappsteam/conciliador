<?php

namespace App\Modules\Integrations\Bank\Providers;

use App\Modules\Integrations\Bank\Models\Bank;
use App\Modules\Integrations\Bank\Policies\BankPolicy;
use App\Modules\Integrations\Bank\Repositories\Contracts\BankRepositoryInterface;
use App\Modules\Integrations\Bank\Repositories\BankRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BankServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Bank::class, BankPolicy::class);
    }
}
