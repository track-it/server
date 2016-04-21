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

    Route::model('proposal', 'Trackit\Models\Proposal');
    Route::resource('proposals/{proposal}/attachments', 'AttachmentController');

    Route::get('attachments/{attachment}/download', [ 'as' => 'attachments.download', 'uses' => 'AttachmentController@download']);

});
