<?php

namespace App\Modules\ERP\Position\Providers;

use App\Modules\ERP\Position\Models\Position;
use App\Modules\ERP\Position\Policies\PositionPolicy;
use App\Modules\ERP\Position\Repositories\Contracts\PositionRepositoryInterface;
use App\Modules\ERP\Position\Repositories\PositionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PositionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Position::class, PositionPolicy::class);
    }
}
