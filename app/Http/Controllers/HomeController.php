<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Product;
use App\OrderInvoice;
use App\SalesInvoice;
use App\OrderInvoiceDetails;
use App\Http\Resources\OrderInvoice as OrderInvoiceResources;
use App\Http\Resources\OrderInvoiceDetail as OrderInvoiceDetailsResources;
use DB;

class HomeController extends Controller
{
    public function grand_totals()
    {
        $grand_total1 = SalesInvoice::sum('grand_total');
        $grand_total2 = OrderInvoice::sum('grand_total');
        $ret = array($grand_total1, $grand_total2);
        return ['data' => $ret];
    }

    public function net_worth_total()
    {
        $grand_total1 = OrderInvoice::sum('grand_total');
        $grand_total2 = SalesInvoice::sum('grand_total');
        $net = $grand_total2 - $grand_total1;
        return ['data' => $net];
    }

    
    public function most_bought_products()
    {
        $count = DB::table('sales_invoice_details')
        ->select('product_id', DB::raw('count(*) as total'))
        ->groupBy('product_id')
        ->get();

        $products = DB::table('sales_invoice_details')
        ->join('products', 'products.id', '=', 'sales_invoice_details.product_id')
        ->select('product_name', DB::raw('count(*) as total'))
        ->groupBy('product_name')
        ->get();
        $ret = array($products);
        return ['data' => $products];
    }
}
