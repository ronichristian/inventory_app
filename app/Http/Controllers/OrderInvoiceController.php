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
use Auth;
use App\User;

class OrderInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_invoice = OrderInvoice::orderBy('created_at', 'desc')->get();
        return new OrderInvoiceResources($order_invoice);
    }

    public function get_order_invoices(Request $request)
    {
        $columns = ['id', 'created_at', 'grand_total'];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = OrderInvoice::select('id', 'created_at', 'grand_total')->orderBy($columns[$column], $dir);

        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('id', 'like', '%' . $searchValue . '%')->
                orWhere('created_at', 'like', '%' . $searchValue . '%');
            });
        }
        $order_invoice = $query->paginate($length);
        return ['data' => $order_invoice, 'draw' => $request->input('draw')];
    }

    public function grand_total()
    {
        $grand_total = OrderInvoice::sum('grand_total');
        $ret = array($grand_total);
        return new OrderInvoiceResources($ret);
    }


    public function order_details($id)
    {
        $order_invoice = DB::table('order_invoices')
        ->join('order_invoice_details', 'order_invoice_details.order_invoice_id', '=', 'order_invoices.id')
        ->join('products', 'products.id', '=', 'order_invoice_details.product_id')
        ->select('products.product_name', 'order_invoice_details.product_srp', 'order_invoice_details.product_qty', 'order_invoice_details.sub_total', 'order_invoices.grand_total')
        ->where('order_invoice_details.order_invoice_id', $id)
        ->get();

        return new OrderInvoiceDetailsResources($order_invoice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order_invoice = new OrderInvoice;
        $order_invoice->user_id = 2;
        $order_invoice->grand_total = 0;
        $order_invoice->save();

        for($i=0; $i < count($request->all()); $i++)
        {
            $order_invoice_id = DB::table('order_invoices')->orderBy('id',"DESC")->take(1)->value('id');
            $order_invoice_details = new OrderInvoiceDetails;
            $order_invoice_details->order_invoice_id = $order_invoice_id;
            $order_invoice_details->product_id = $request[$i]['product_id'];
            $order_invoice_details->product_srp = $request[$i]['product_srp'];
            $order_invoice_details->product_qty = $request[$i]['product_qty'];
            $order_invoice_details->sub_total = $request[$i]['product_srp'] * $request[$i]['product_qty'];
            $order_invoice_details->save();
            
            
            $grand_total = OrderInvoiceDetails::where('order_invoice_id', $order_invoice_id)->sum('sub_total');
            $order_invoice = OrderInvoice::find($order_invoice_id);
            $order_invoice->grand_total = $grand_total;
            $order_invoice->save();

            $products = Product::findOrFail($request[$i]['product_id']);
            $products->srp = $request[$i]['product_srp'];
            $products->product_qty = $products->product_qty + $request[$i]['product_qty'];
            $products->save();
        }

        return new OrderInvoiceDetailsResources($order_invoice_details);
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
