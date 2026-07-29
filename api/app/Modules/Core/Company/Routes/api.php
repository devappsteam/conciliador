<?php

use App\Modules\Core\Company\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('companies', CompanyController::class)
    ->parameters(['companies' => 'uuid']);
