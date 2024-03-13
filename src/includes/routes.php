<?php

use App\Http\Route;

Route::get('/', '\\App\\Http\\Controllers\\HomeController@index')->setName('home');

Route::get('/api/auth', '\\App\\Http\\Controllers\\ApiAuthController@get')->setName('api_auth_get');
Route::post('/api/user', '\\App\\Http\\Controllers\\ApiUserController@post')->setName('api_user_post');
Route::put('/api/user', '\\App\\Http\\Controllers\\ApiUserController@put')->setName('api_user_put');
Route::put('/api/result', '\\App\\Http\\Controllers\\ApiResultController@put')->setName('api_result_put');

Route::get('/admin', '\\App\\Http\\Controllers\\AdminController@index')->setName('admin_home');
Route::get('/admin/game', '\\App\\Http\\Controllers\\AdminGameController@get')->setName('admin_game');
Route::get('/admin/game/{game_id}', '\\App\\Http\\Controllers\\AdminGameController@getWithGameId')->setName('admin_game_edit');
Route::get('/admin/user', '\\App\\Http\\Controllers\\AdminUserController@get')->setName('admin_user');
Route::get('/admin/user/{user_id?}', '\\App\\Http\\Controllers\\AdminUserController@getWithUserId')->setName('admin_user_detail');

Route::get('/page', '\\App\\Http\\Controllers\\PageController@index')->setName('page');
Route::get('/page/game/{game_id}', '\\App\\Http\\Controllers\\PageGameController@get')->setName('page_game');
Route::get('/page/game/{game_id}/ranking', '\\App\\Http\\Controllers\\PageGameController@rankingGet')->setName('page_ranking');
Route::get('/page/user/{user_id}', '\\App\\Http\\Controllers\\PageUserController@get')->setName('page_user');
Route::get('/page/user/{user_id}/history/{game_id}', '\\App\\Http\\Controllers\\PageUserController@historyGet')->setName('page_user_history');
