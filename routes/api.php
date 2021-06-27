<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\CovenantController;
use App\Http\Controllers\CreditSimulationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api.auth')->group(function () {
    Route::get('institutions', [InstitutionController::class, 'index']);
    Route::get('covenants', [CovenantController::class, 'index']);

    Route::post('credit-simulation', [CreditSimulationController::class, 'index']);
});
