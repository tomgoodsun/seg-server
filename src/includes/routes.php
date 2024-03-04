<?php

use App\Http\Route;

Route::get('/', '\\App\\Http\\Controllers\\HomeController@index')->setName('home');
