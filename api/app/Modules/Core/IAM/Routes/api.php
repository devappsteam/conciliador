<?php

use App\Modules\Core\IAM\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RoleController::class)
    ->parameters(['roles' => 'uuid']);
