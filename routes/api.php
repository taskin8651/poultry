<?php

Route::group(['prefix' => 'v1','as' => 'api.','namespace' => 'Api\V1\Admin'], function () {

    // 🔐 User Register
    Route::post('register', 'AuthApiController@register')->name('register');


    // 🔐 User Login
    Route::post('login', 'AuthApiController@login')->name('login');

    // 👤 User Profile By ID
    Route::get('user-profile/{id}', 'AuthApiController@profile')->name('user.profile');

    // 🛒 All Products
    Route::get('products','ProductApiController@index')->name('products.index');

    // 🛒 Single Product
    Route::get('products/{id}','ProductApiController@show')->name('products.show');

    // Orderss
    Route::post('orders', 'OrderApiController@store')->name('orders.store');

    Route::get('orders/{user_id}', 'OrderApiController@index')->name('orders.index');

    Route::get('order/{user_id}/{id}','OrderApiController@show')->name('orders.show');

});
