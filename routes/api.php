<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh'); 
       
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::resource('/user-management', 'UserController', [
	    'except' => ['edit', 'create']
	])->middleware('isAdmin');

	Route::resource('/role', 'RoleController', [
	    'except' => ['edit', 'create']
	])->middleware('isAdmin');

	Route::resource('/expense-category', 'ExpenseCategoryController', [
	    'except' => ['edit', 'create', 'index']
	])->middleware('isAdmin');

	Route::resource('/expense-category', 'ExpenseCategoryController', [
	    'except' => ['edit', 'create', 'store', 'show', 'update', 'destroy']
	]);

	Route::get('/expense/user/{user_id}', 'ExpenseController@getByUser');
	Route::get('/expense/chart/{user_id}', 'ExpenseController@getChart');
	Route::resource('/expense', 'ExpenseController', [
	    'except' => ['edit', 'create']
	]);

	//change password
	Route::post('/change-password', 'ChangePassController@store');
});