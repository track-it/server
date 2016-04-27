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
Route::group([], function () {
    Route::post('/auth/login', 'AuthController@login');
    Route::get('proposals', 'ProposalController@index');
    Route::get('proposals/{proposal}', 'ProposalController@show');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::singularResourceParameters();
    
    // Define models
    Route::model('proposal', 'Trackit\Models\Proposal');
    Route::model('comment', 'Trackit\Models\Comment');

    // Comment routes
    Route::resource('proposals/{proposal}/comments', 'CommentController', ['only' => ['index', 'store']]);
    Route::resource('comments', 'CommentController', ['only' => ['show', 'update', 'destroy']]);

    // Tag routes
    Route::resource('proposals/{proposal}/tags', 'TagController', ['only' => ['index', 'store']]);
    Route::resource('tags', 'TagController', ['only' => ['show', 'update', 'destroy']]);

    // Proposal routes
    Route::resource('proposals', 'ProposalController', ['except' => ['index', 'show']]);
    Route::resource('proposals/{proposal}/attachments', 'AttachmentController', ['only' => ['index', 'store']]);
    
    // Global Attachment routes
    Route::resource('attachments', 'AttachmentController', ['only' => ['show', 'update', 'destroy']]);
    Route::get('attachments/{attachment}/download', [ 'as' => 'attachments.download', 'uses' => 'AttachmentController@download']);
});
