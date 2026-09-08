<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - Nochi Farm Input
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Endpoint Input Aksi Cepat
Route::post('/production/store', [DashboardController::class, 'storeEggProduction'])->name('production.store');
Route::post('/feed/store', [DashboardController::class, 'storeFeedConsumption'])->name('feed.store');
Route::post('/mortality/store', [DashboardController::class, 'storeMortality'])->name('mortality.store');
Route::post('/weight/store', [DashboardController::class, 'storeWeightSample'])->name('weight.store');
Route::post('/health/store', [DashboardController::class, 'storeHealthTreatment'])->name('health.store');
