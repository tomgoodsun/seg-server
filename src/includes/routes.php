<?php

use App\Http\Route;

Route::get('/', '\\App\\Http\\Controllers\\HomeController@index')->setName('home');
Route::get('/admin', '\\App\\Http\\Controllers\\AdminController@index')->setName('admin_home');
Route::get('/admin/game', '\\App\\Http\\Controllers\\AdminGameController@index')->setName('admin_game');
Route::get('/admin/game/{game_id}', '\\App\\Http\\Controllers\\AdminGameController@game')->setName('admin_game_edit');
Route::get('/admin/user', '\\App\\Http\\Controllers\\AdminUserController@index')->setName('admin_user');
Route::get('/admin/user/{user_id?}', '\\App\\Http\\Controllers\\AdminUserController@user')->setName('admin_user_detail');

Route::get('/page', '\\App\\Http\\Controllers\\PageController@index')->setName('page');
Route::get('/page/game/{game_id}', '\\App\\Http\\Controllers\\PageGameController@index')->setName('page_game');
Route::get('/page/game/{game_id}/ranking', '\\App\\Http\\Controllers\\PageGameController@ranking')->setName('page_ranking');
Route::get('/page/user/{user_id}', '\\App\\Http\\Controllers\\PageUserController@index')->setName('page_user');
Route::get('/page/user/{user_id}/history/{game_id}', '\\App\\Http\\Controllers\\PageUserController@history')->setName('page_user_history');
