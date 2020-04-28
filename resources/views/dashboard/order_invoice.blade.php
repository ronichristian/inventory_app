@extends('layouts.app')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">
        Order Invoice
        <a href="/orders" class="btn btn-info btn-sm float-right">Go Back</a>
    </h1> 
    
    <!-- DataTales Example -->
    <div id="order_invoice">
        <order_invoice />
    </div>
    
        
@endsection