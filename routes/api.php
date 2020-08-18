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
// Categories List
Route::get('categories', 'ProductsController@categories');
// Create Category
Route::post('categories', 'ProductsController@store_category');
// Delete Category
Route::delete('categories/{id}', 'ProductsController@destroy_category');
// Update Category
Route::put('categories', 'ProductsController@store_category');


// List Products
Route::get('products', 'ProductsController@index');
Route::get('/get_products', 'ProductsController@get_products');
Route::get('/get_products_joined', 'ProductsController@get_products_joined');
// Create new Product
Route::post('product', 'ProductsController@store');
// Update Product
Route::put('product', 'ProductsController@store');
// Delete Product
Route::delete('product/{id}', 'ProductsController@destroy');


// List Products
Route::get('/my_products', 'ProductsController@my_products');
Route::get('/get_my_products', 'ProductsController@get_my_products');
Route::get('/get_my_products_joined', 'ProductsController@get_my_products_joined');


// List of Invoices
Route::get('get_sales_invoices', 'SalesInvoiceController@get_sales_invoices');
Route::get('sales_invoice_gt', 'SalesInvoiceController@grand_total');
// Create Sales Invoice
Route::post('sales_invoice', 'SalesInvoiceController@store');
// Sales Invoice Details
Route::get('sales_invoice_details/{id}', 'SalesInvoiceController@invoice_details');



// Get Orders
Route::get('get_order_invoices', 'OrderInvoiceController@get_order_invoices');
Route::get('order_invoice_gt', 'OrderInvoiceController@grand_total');
// Order Details
Route::get('order_invoice_details/{id}', 'OrderInvoiceController@order_details');
// Create Order
Route::post('order_invoice', 'OrderInvoiceController@store');


// Get 
Route::get('grand_totals', 'HomeController@grand_totals');
Route::get('net_worth_total', 'HomeController@net_worth_total');
Route::get('/most_bought_products', 'HomeController@most_bought_products');
