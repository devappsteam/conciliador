<?php

use App\Modules\Core\IAM\Http\Controllers\PermissionController;
use App\Modules\Core\IAM\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RoleController::class)->parameters(['roles' => 'uuid']);

Route::prefix('permissions')->group(function () {
    Route::get('/all', [PermissionController::class, 'all']);
    Route::get('/', [PermissionController::class, 'index']);
    Route::get('/{uuid}', [PermissionController::class, 'show']);
});
