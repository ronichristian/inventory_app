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

Route::get('/', 'PagesController@index')->middleware('auth');
Route::get('/home', 'PagesController@home')->middleware('auth')->name('home');
Route::get('/products', 'PagesController@products')->middleware('auth');
Route::get('/transaction', 'PagesController@transaction')->middleware('auth');
Route::get('/sales_invoice', 'PagesController@sales_invoice')->middleware('auth');
Route::get('/category', 'PagesController@category')->middleware('auth');
Route::get('/orders', 'PagesController@orders')->middleware('auth');
Route::get('/order_invoice', 'PagesController@order_invoice')->middleware('auth');
Route::get('/display_products', 'PagesController@display_products')->middleware('auth');

Route::resource('/users', 'Admin\UserController');

Route::namespace('Admin')->prefix('admin')->middleware('auth')->name('admin.')->group(function(){
    Route::resource('/users', 'UserController');
});

Route::get('/chats', 'ChatsController@index');
Route::get('/messages', 'ChatsController@fetchMessages');
Route::post('/messages', 'ChatsController@sendMessage');

