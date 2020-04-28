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

Route::get('/', 'PagesController@index')->name('home');
Route::get('/products', 'PagesController@products');
Route::get('/transaction', 'PagesController@transaction');
Route::get('/sales_invoice', 'PagesController@sales_invoice');
Route::get('/category', 'PagesController@category');
Route::get('/orders', 'PagesController@orders');
Route::get('/order_invoice', 'PagesController@order_invoice');

