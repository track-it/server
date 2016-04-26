<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group([], function() {
	Route::post('/auth/login', 'AuthController@login');
});

Route::get('/proposals', 'ProposalController@index');

Route::get('/proposals/{proposal}', 'ProposalController@show');


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::put('/proposals/{proposal}', 'ProposalController@update');
    Route::delete('/proposals/{proposal}', 'ProposalController@destroy');
    Route::post('/proposals', 'ProposalController@create');
});
