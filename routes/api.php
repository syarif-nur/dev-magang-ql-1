<?php


use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\SatuanBarangController;
use App\Http\Controllers\TransaksiController;
//Master Barang
Route::get('/master-barang', [MasterBarangController::class, 'index'])->name('master_barang');
Route::get('/master-barang/{id}', [MasterBarangController::class, 'show']);
Route::post('/master-barang', [MasterBarangController::class, 'store']);
Route::put('/master-barang/{id}', [MasterBarangController::class, 'update']);
Route::delete('/master-barang/{id}', [MasterBarangController::class, 'destroy']);

//Satuan Barang
Route::get('/satuan-barang', [SatuanBarangController::class, 'index'])->name( 'satuanbarang');
Route::get('/satuan-barang/{id}', [SatuanBarangController::class, 'show']);
Route::post('/satuan-barang', [MasterBarangController::class, 'store']);
Route::get('/barang-by-satuan', [MasterBarangController::class, 'getSatuanByMasterBarang']);


//Transaksi
Route::post('/transaksi', [TransaksiController::class, 'store']);


