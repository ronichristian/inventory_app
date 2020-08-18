@extends('layouts.apps')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">ORDERS</h1>
    <div class="text-right"> 
        
    </div> 
    
    <!-- DataTales Example -->
    <div id="orders">
        <orders :user="{{ auth()->user()->id }}" />
    </div>
    
        
@endsection