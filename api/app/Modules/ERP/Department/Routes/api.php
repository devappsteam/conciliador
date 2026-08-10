<?php

use App\Modules\ERP\Department\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('departments')->group(function () {
    Route::get('all', [DepartmentController::class, 'all']);
});
Route::apiResource('departments', DepartmentController::class)->parameters(['departments' => 'uuid']);
