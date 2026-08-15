<?php

Route::group(['prefix' => 'v1','as' => 'api.','namespace' => 'Api\V1\Admin'], function () {

    // 🔐 Login
    Route::post('login', 'AuthApiController@login')->name('login');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::post('logout', 'AuthApiController@logout')
            ->name('logout');

        Route::get('user', 'AuthApiController@user')
            ->name('user');

        // Epaper
        Route::post('epapers/media', 'EpaperApiController@storeMedia')
            ->name('epapers.storeMedia');

        Route::apiResource('epapers', 'EpaperApiController');
    });
});
