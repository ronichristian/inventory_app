@extends('layouts.app')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">
        TRANSACTION
        <a href="/sales_invoice" class="btn btn-primary btn-sm float-right" type="submit">Sales Invoice</a>
    </h1>
    
    <!-- DataTales Example -->
    <div id="transaction">
        <transaction />
    </div>
    
    
@endsection