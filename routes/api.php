<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MasterBarangController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// master_barang - satuan
Route::prefix('barang_satuan')->group(function () {
    Route::get('/', [MasterBarangController::class, 'index']);
    Route::post('/', [MasterBarangController::class, 'store']);
    Route::get('/{id}', [MasterBarangController::class, 'show']);
    Route::put('/{id}', [MasterBarangController::class, 'update']);
    Route::delete('/{id}', [MasterBarangController::class, 'destroy']);
});
// Route::apiResource('/barang_satuan', App\HttpControllers\Api\MasterBarangController::class);
