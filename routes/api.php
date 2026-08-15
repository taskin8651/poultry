<?php

Route::group(['prefix' => 'v1','as' => 'api.','namespace' => 'Api\V1\Admin'], function () {

    // 🔐 User Register
    Route::post('register', 'AuthApiController@register')->name('register');


    // 🔐 User Login
    Route::post('login', 'AuthApiController@login')->name('login');

    // 👤 User Profile By ID
    Route::get('user-profile/{id}', 'AuthApiController@profile')->name('user.profile');

    // 🛒 Product Details
    Route::get('products/{id}','ProductApiController@show')->name('products.show');

    // 💰 Product Price By Quantity
    Route::get('products/{id}/price','ProductApiController@price')->name('products.price');

});
