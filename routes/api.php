<?php

use App\Http\Controllers\Api\MobilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Endpoint CRUD Mobil (Tema Showroom Mobil)
Route::prefix('mobil')->name('mobil.')->group(function () {
    Route::get('/', [MobilController::class, 'index'])->name('index');
    Route::post('/', [MobilController::class, 'store'])->name('store');
    Route::get('/{id}', [MobilController::class, 'show'])->name('show');
    Route::match(['put', 'patch'], '/{id}', [MobilController::class, 'update'])->name('update');
    Route::delete('/{id}', [MobilController::class, 'destroy'])->name('destroy');
});

// Endpoint User (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
