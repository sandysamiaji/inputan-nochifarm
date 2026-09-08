<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\RekapController;

/*
|--------------------------------------------------------------------------
| Web Routes - Nochi Farm Input
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Endpoint Input Aksi Cepat Kandang
Route::post('/production/store', [DashboardController::class, 'storeEggProduction'])->name('production.store');
Route::post('/feed/store', [DashboardController::class, 'storeFeedConsumption'])->name('feed.store');
Route::post('/mortality/store', [DashboardController::class, 'storeMortality'])->name('mortality.store');
Route::post('/weight/store', [DashboardController::class, 'storeWeightSample'])->name('weight.store');
Route::post('/health/store', [DashboardController::class, 'storeHealthTreatment'])->name('health.store');

// Modul Gudang (Warehouse)
Route::prefix('gudang')->name('warehouse.')->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->name('index');
    Route::get('/telur', [WarehouseController::class, 'telur'])->name('telur');
    Route::get('/pakan', [WarehouseController::class, 'pakan'])->name('pakan');
    Route::get('/obat', [WarehouseController::class, 'obat'])->name('obat');
    Route::post('/store', [WarehouseController::class, 'store'])->name('store');
    Route::put('/{id}/update', [WarehouseController::class, 'update'])->name('update');
    Route::patch('/{id}/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('toggle-status');
    Route::delete('/{id}/destroy', [WarehouseController::class, 'destroy'])->name('destroy');
});

// Modul Rekap Data (Reporting & Analytics)
Route::prefix('rekap')->name('rekap.')->group(function () {
    Route::get('/', [RekapController::class, 'index'])->name('index');
    Route::get('/detail', [RekapController::class, 'detail'])->name('detail');
    Route::get('/export-excel', [RekapController::class, 'exportExcel'])->name('export-excel');
});


