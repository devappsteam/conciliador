<?php

use App\Modules\Core\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)
    ->parameters(['users' => 'uuid']);
