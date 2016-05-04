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

Route::group([
    'prefix' => config('saml2_settings.routesPrefix'),
    'middleware' => ['saml'],
], function () {
    Route::get('logout', array(
        'as' => 'saml_logout',
        'uses' => 'Saml2Controller@logout',
    ));
    Route::get('login', array(
        'as' => 'saml_login',
        'uses' => 'Saml2Controller@login',
    ));
    Route::get('metadata', array(
        'as' => 'saml_metadata',
        'uses' => 'Saml2Controller@metadata',
    ));
    Route::post('acs', array(
        'as' => 'saml_acs',
        'uses' => 'Saml2Controller@acs',
    ));
    Route::get('sls', array(
        'as' => 'saml_sls',
        'uses' => 'Saml2Controller@sls',
    ));
});

Route::group([], function () {
    
    // Saml2
    Route::get('login', 'AuthController@saml');
    Route::get('error', function () {
        dd($this);
    });

    // Authentication
    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'AuthController@register');
    Route::post('auth/check', 'AuthController@check');

    // Open routes
    Route::get('proposals', 'ProposalController@index');
    Route::get('proposals/{proposal}', 'ProposalController@show');

    // Sitemap
    Route::get('site', function (Request $request) {
        $sitemap = [
            'self' => '/site',
            'auth' => [
                'login' => 'auth/login',
                'register' => 'auth/register',
            ],
            'index' => [
                'proposals' => 'proposals',
                'projects' => 'projects',
            ],
            'show' => [
                'proposals' => 'proposals',
                'projects' => 'projects',
                'attachments' => 'attachments',
                'tags' => 'tags',
                'comments' => 'comments',
            ],
            'store' => [
                'proposals' => 'proposals',
                'projects' => 'projects',
            ],
            'update' => [
                'proposals' => 'proposals',
                'projects' => 'projects',
                'attachments' => 'attachments',
                'tags' => 'tags',
                'comments' => 'comments',
            ],
            'destroy' => [
                'proposals' => 'proposals',
                'projects' => 'projects',
                'attachments' => 'attachments',
                'tags' => 'tags',
                'comments' => 'comments',
            ],
        ];

        $user = Auth::guard()->user();

        if ($user) {
            $sitemap['user'] = $user;
        }

        return $sitemap;
    });
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::singularResourceParameters();
    
    // Define models
    Route::model('proposal', 'Trackit\Models\Proposal');
    Route::model('comment', 'Trackit\Models\Comment');

    // Comment routes
    Route::get('comments/{comment}', 'CommentController@show');
    Route::put('comments/{comment}', 'CommentController@update');
    Route::delete('comments/{comment}', 'CommentController@destroy');

    // Tag routes
    Route::get('tags/{tag}', 'TagController@show');
    Route::put('tags/{tag}', 'TagController@update');
    Route::delete('tags/{tag}', 'TagController@destroy');

    // Project routes
    Route::get('projects/{project}', 'ProjectController@show');
    Route::get('projects', 'ProjectController@index');
    Route::put('projects/{project}', 'ProjectController@update');
    Route::delete('projects/{project}', 'ProjectController@destroy');

    // Team routes
    Route::get('teams/{team}', 'TeamController@show');
    Route::put('teams/{team}', 'TeamController@update');
    Route::delete('teams/{team}', 'TeamController@destroy');

    // Proposal routes
    Route::post('proposals', 'ProposalController@store');
    Route::put('proposals/{proposal}', 'ProposalController@update');
    Route::delete('proposals/{proposal}', 'ProposalController@destroy');
    Route::get('proposals/{proposal}/attachments', 'AttachmentController@index');
    Route::post('proposals/{proposal}/attachments', 'AttachmentController@store');
    Route::get('proposals/{proposal}/tags', 'TagController@index');
    Route::post('proposals/{proposal}/tags', 'TagController@store');
    Route::get('proposals/{proposal}/comments', 'CommentController@index');
    Route::post('proposals/{proposal}/comments', 'CommentController@store');
    Route::get('proposals/{proposal}/projects', 'ProjectController@index');
    Route::post('proposals/{proposal}/projects', 'ProjectController@store');
    Route::get('proposals/{proposal}/teams', 'TeamController@index');
    Route::post('proposals/{proposal}/teams', 'TeamController@store');

    // Global Attachment routes
    Route::get('attachments/{attachment}', 'AttachmentController@show');
    Route::put('attachments/{attachment}', 'AttachmentController@update');
    Route::delete('attachments/{attachment}', 'AttachmentController@destroy');
    Route::get('attachments/{attachment}/download', 'AttachmentController@download');
});
