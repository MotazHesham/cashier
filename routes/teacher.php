<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'namespace' => 'Teacher', 'middleware' => ['auth','teacher']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::post('get_products','HomeController@get_products')->name('get_products');
    Route::post('get_attributes','HomeController@get_attributes')->name('get_attributes');
    Route::post('add_product','HomeController@add_product')->name('add_product');
    Route::post('send_order','HomeController@send_order')->name('send_order');

    // Orders
    Route::post('update_order','OrdersController@update')->name('update_order');
    Route::resource('orders', 'OrdersController');
});
