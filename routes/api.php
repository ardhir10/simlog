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
Route::get('/getdashboard/{id}/{type}', 'ApiSbnpController@getDashboard')->name('api.sbnp');

// Integration API
Route::post('/v1/get-sbnp', 'ApiAtonrepController@getSbnp')->name('api-atonrep.sbnp');
Route::get('/v1/get-secret', 'ApiAtonrepController@getSecret')->name('api-atonrep.getSecret');

Route::patch('/v1/get-sbnp-detail', 'ApiAtonrepController@getSbnpDetail')->name('api-atonrep.sbnp-detail');


