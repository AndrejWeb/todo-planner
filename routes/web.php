<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/filter/{filter?}', 'TodosController@filter')->name('filter');

    Route::get('/', 'TodosController@index');
    Route::post('/add-todo', 'TodosController@add');
    Route::put('/completed/{id}', 'TodosController@completed');
    Route::put('/update-todo/{id}', 'TodosController@update');
    Route::delete('/delete/all', 'TodosController@delete_all');
    Route::delete('/delete/{id}', 'TodosController@delete');

    Route::group(['prefix' => 'user'], function() {
        Route::put('/{id}/pagination', 'UsersController@update_pagination')->name('pagination');
        Route::get('/{id}', 'UsersController@index')->name('profile');
        Route::put('/{id}', 'UsersController@update')->name('update_account');
        Route::delete('/{id}', 'UsersController@delete')->name('delete_account');
    });

});
