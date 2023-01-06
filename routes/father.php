<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'father', 'as' => 'father.', 'namespace' => 'Father', 'middleware' => ['auth','father']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::post('orders/details', 'HomeController@details')->name('orders.details');

    Route::get('payments', 'PaymentsController@index')->name('payments.index');
    Route::post('payments/transfer', 'PaymentsController@transfer')->name('payments.transfer');

    Route::resource('students', 'StudentsController');

});
