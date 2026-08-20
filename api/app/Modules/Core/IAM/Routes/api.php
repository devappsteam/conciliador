<?php

use App\Modules\Core\IAM\Http\Controllers\PermissionController;
use App\Modules\Core\IAM\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('roles', RoleController::class)->parameters(['roles' => 'uuid']);

    Route::prefix('permissions')->group(function () {
        Route::get('/all', [PermissionController::class, 'all']);
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{uuid}', [PermissionController::class, 'show']);
    });
});
