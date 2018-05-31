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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/user', function(Request $request) {
//     return $request->user();
// })->middleware('client');

Route::middleware('client')->group( function () {
	Route::resource('insureds', 'InsuredController',['only' => ['index','store']]);
	Route::resource('rates', 'RateController',['only' => ['index']]);

	Route::get('rates/premi/{pawal}/{pakhir}/{tsi}',[
			'as'		=>	'rates.premi.index',
			'uses'	=>	'RateController@index'
	]);
});

Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});
