<?php

Route::get('/auth/login', 'Auth\CustomAuthController@login')->name('login');
Route::post('/auth/login', 'Auth\CustomAuthController@checkLogin');

Route::get('/auth/register', 'Auth\CustomAuthController@register');
Route::post('/auth/register', 'Auth\CustomAuthController@checkRegister');
Route::get('/auth/logout', 'Auth\CustomAuthController@logout');
