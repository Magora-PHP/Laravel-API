<?php

/**
 * @SWG\Info(
 *     title="az-laravel-starter-kit api docs",
 *     version="0.0.0",
 *     description="code '0' means successful operation, like code:'success'",
 * )
 * @SWG\Swagger(basePath="/api/v1/")
 */

//\Debugbar::disable();

Route::get('/', function () {
    return view('welcome');
});


//Route::get('profiles', 'ProfileController@listing');


Route::group([
    'prefix' => 'web',
    'middleware' => [
        'web.EncryptCookies',
        'web.AddQueuedCookiesToResponse',
        'web.StartSession',
        'web.ShareErrorsFromSession',
        'web.VerifyCsrfToken',
    ]
], function () {
    Route::get('profiles', 'WebProfileController@listing');
    Route::get('profiles/create', 'WebProfileController@create');
    Route::post('profiles', 'WebProfileController@store');
});

Route::group([
    'prefix' => 'api/v1',
//    'middleware' => ['api.jwt']
], function () {
    Route::get('profiles', 'ApiProfileController@listing');
    Route::post('profiles', 'ApiProfileController@store');
});
