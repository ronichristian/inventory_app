@extends('layouts.app')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">
        SALES INVOICE
        <a href="/transaction"  class="btn btn-primary btn-sm float-right" type="submit">Go Back</a>
    </h1>
    
    <!-- DataTales Example -->
    <div id="sales_invoice">
        <sales_invoice />
    </div>
    
        
@endsection