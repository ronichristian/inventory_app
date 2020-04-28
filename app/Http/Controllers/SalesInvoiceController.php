<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SalesInvoice;
use App\SalesInvoiceDetail;
use App\Product;
use App\Http\Resources\SalesInvoice as SalesInvoiceResources;
use App\Http\Resources\SalesInvoiceDetail as SalesInvoiceDetailResources;
use DB;

class SalesInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales_invoice = SalesInvoice::orderBy('created_at', 'desc')->get();
        return new SalesInvoiceResources($sales_invoice);
    }

    public function get_sales_invoices(Request $request)
    {
        $columns = ['id', 'created_at', 'grand_total'];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = SalesInvoice::select('id', 'created_at', 'grand_total')->orderBy($columns[$column], $dir);

        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('id', 'like', '%' . $searchValue . '%')->
                orWhere('created_at', 'like', '%' . $searchValue . '%');
            });
        }
        $sales_invoice = $query->paginate($length);
        return ['data' => $sales_invoice, 'draw' => $request->input('draw')];
    }

    // Get Sales Invoice Grand Total
    public function grand_total()
    {
        $grand_total = SalesInvoice::sum('grand_total');
        $ret = array($grand_total);
        return new SalesInvoiceResources($ret);
    }

    // Get Sales Invoice Detail
    public function invoice_details($id)
    {
        $sales_invoice = DB::table('sales_invoices')
        ->join('sales_invoice_details', 'sales_invoice_details.sales_invoice_id', '=', 'sales_invoices.id')
        ->join('products', 'products.id', '=', 'sales_invoice_details.product_id')
        ->select('products.product_name', 'products.product_price', 'sales_invoice_details.product_qty', 'sales_invoice_details.sub_total', 'sales_invoices.grand_total')
        ->where('sales_invoice_details.sales_invoice_id', $id)
        ->get();
        return new SalesInvoiceDetailResources($sales_invoice);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $finalArray = array($request);

        $sales_invoice = new SalesInvoice;
        $sales_invoice->grand_total = 0;
        $sales_invoice->save();

        for($i=0; $i < count($request->all()); $i++)
        {
            $sales_invoice_id = DB::table('sales_invoices')->orderBy('id',"DESC")->take(1)->value('id');

            $sales_invoice_details = new SalesInvoiceDetail;
            $sales_invoice_details->sales_invoice_id = $sales_invoice_id;
            $sales_invoice_details->product_id = $request[$i]['id'];
            $sales_invoice_details->product_price = $request[$i]['product_price'];
            $sales_invoice_details->product_qty = $request[$i]['product_qty'];
            $sales_invoice_details->sub_total = $request[$i]['product_price'] * $request[$i]['product_qty'];
            $sales_invoice_details->save();

            $grand_total = SalesInvoiceDetail::where('sales_invoice_id', $sales_invoice_id)->sum('sub_total');
            $sales_invoice = SalesInvoice::find($sales_invoice_id);
            $sales_invoice->grand_total = $grand_total;
            $sales_invoice->save();

            $products = Product::findOrFail($request[$i]['id']);
            $products->product_qty = $products->product_qty - $request[$i]['product_qty'];
            $products->save();
        }

        return new SalesInvoiceDetailResources($sales_invoice_details);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
