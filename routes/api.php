<?php

use Illuminate\Http\Request;

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

/*

Route::post('leads', function (Request $request) {
    return response()->json($request->headers->all());
});
*/
 Route::post('leads', 'Api\LeadsController@store')->name('api.leads.store');



 



