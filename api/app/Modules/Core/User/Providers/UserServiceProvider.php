<?php

namespace App\Modules\Core\User\Providers;

use App\Modules\Core\User\Models\User;
use App\Modules\Core\User\Policies\UserPolicy;
use App\Modules\Core\User\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\Core\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
    }
}
