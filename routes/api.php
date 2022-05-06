<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/v1/nilai-barang-masuk', 'ApiDashboardController@nilaiBarangMasuk')->name('api.nilai-barang-masuk');
Route::post('/v1/nilai-barang-keluar', 'ApiDashboardController@nilaiBarangKeluar')->name('api.nilai-barang-keluar');
Route::post('/v1/nilai-saldo-barang', 'ApiDashboardController@saldoBarang')->name('api.saldo-barang');
Route::post('/v1/permintaan-disetujui', 'ApiDashboardController@permintaanDisetujui')->name('api.permintaan-disetujui');
Route::post('/v1/permintaan-ditolak', 'ApiDashboardController@permintaanDitolak')->name('api.permintaan-ditolak');
Route::post('/v1/permintaan-dalamproses', 'ApiDashboardController@permintaanDalamproses')->name('api.permintaan-dalamproses');
Route::post('/v1/nilai-distribusi', 'ApiDashboardController@nilaiDistribusi')->name('api.nilai-distribusi');
Route::post('/v1/nilai-belum-distribusi', 'ApiDashboardController@nilaiBelumDistribusi')->name('api.nilai-belumdistribusi');
Route::post('/v1/rencana-tahunan', 'ApiDashboardController@rencanaTahunan')->name('api.rencana-tahunan');




