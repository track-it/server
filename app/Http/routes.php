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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::singularResourceParameters();

    Route::get('/proposals', 'ProposalController@index');

    Route::get('/proposals/{proposal}', 'ProposalController@show');

    Route::put('/proposals/{proposal}', 'ProposalController@update');

    Route::delete('/proposals/{proposal}', 'ProposalController@destroy');

    Route::post('/proposals', 'ProposalController@create');

    Route::model('proposal', 'Trackit\Models\Proposal');

    Route::model('comment', 'Trackit\Models\comment');

    Route::resource('proposals/{proposal}/comments', 'CommentController');
});
