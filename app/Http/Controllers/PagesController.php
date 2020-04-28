<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class PagesController extends Controller
{
    public function index()
    {
        return view('dashboard.home');
    }

    public function products()
    {
        return view('dashboard.product');
    }

    public function transaction()
    {
        return view('dashboard.transaction');
    }

    public function sales_invoice()
    {
        return view('dashboard.sales_invoice');
    }

    public function category()
    {
        return view('dashboard.category');
    }

    public function orders()
    {
        return view('dashboard.orders');
    }

    public function order_invoice()
    {
        return view('dashboard.order_invoice');
    }

   

}
