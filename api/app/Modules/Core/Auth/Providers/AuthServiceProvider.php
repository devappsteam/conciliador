<?php

namespace App\Modules\Core\Auth\Providers;

use App\Modules\Core\Auth\Models\Auth;
use App\Modules\Core\Auth\Policies\AuthPolicy;
use App\Modules\Core\Auth\Repositories\Contracts\AuthRepositoryInterface;
use App\Modules\Core\Auth\Repositories\AuthRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        //Gate::policy(Auth::class, AuthPolicy::class);
    }
}
