<?php

use App\Http\Route;

Route::get('/', '\\App\\Http\\Controllers\\HomeController@index')->setName('home');
Route::get('/admin', '\\App\\Http\\Controllers\\AdminController@index')->setName('home');
