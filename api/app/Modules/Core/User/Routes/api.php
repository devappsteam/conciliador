<?php

use App\Modules\Core\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('users', UserController::class)
        ->parameters(['users' => 'uuid']);
});
