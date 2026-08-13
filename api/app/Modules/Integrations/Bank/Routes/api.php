<?php

use App\Modules\Integrations\Bank\Http\Controllers\BankController;
use Illuminate\Support\Facades\Route;

Route::get('banks/all', [BankController::class, 'all']);
Route::apiResource('banks', BankController::class)->parameters(['banks' => 'uuid']);
