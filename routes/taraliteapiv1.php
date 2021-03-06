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
	Route::get('insureds/{cawal}/{cakhir}', [
			'as'		=>	'insureds.index',
			'uses'	=>	'InsuredController@index'
		]);
		Route::get('insureds/{noKontrak}', [
				'as'		=>	'insureds.show',
				'uses'	=>	'InsuredController@show'
			]);
	Route::resource('insureds', 'InsuredController',['only' => ['store']]);
	Route::resource('endors', 'EndorsController', ['only'=> ['store']]);
	Route::resource('rates', 'RateController',['only' => ['index']]);
	Route::get('premi/{pawal}/{pakhir}/{tsi}',[
			'as'		=>	'premi.index',
			'uses'	=>	'RateController@index'
	]);
});

Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});
