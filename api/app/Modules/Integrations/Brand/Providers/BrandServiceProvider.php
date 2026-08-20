<?php

namespace App\Modules\Integrations\Brand\Providers;

use App\Modules\Integrations\Brand\Models\Brand;
use App\Modules\Integrations\Brand\Policies\BrandPolicy;
use App\Modules\Integrations\Brand\Repositories\Contracts\BrandRepositoryInterface;
use App\Modules\Integrations\Brand\Repositories\BrandRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Brand::class, BrandPolicy::class);
    }
}
