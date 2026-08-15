<?php

Route::group(['prefix' => 'v1','as' => 'api.','namespace' => 'Api\V1\Admin'], function () {

    // 🔐 User Register
    Route::post('register', 'AuthApiController@register')->name('register');


    // 🔐 User Login
    Route::post('login', 'AuthApiController@login')->name('login');

    // 👤 User Profile By ID
    Route::get('user-profile/{id}', 'AuthApiController@profile')->name('user.profile');

});
