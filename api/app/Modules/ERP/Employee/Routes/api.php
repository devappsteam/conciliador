<?php

use App\Modules\ERP\Employee\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('employees', EmployeeController::class)
    ->parameters(['employees' => 'uuid']);
