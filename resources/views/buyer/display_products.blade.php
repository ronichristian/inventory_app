@extends('layouts.apps')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">MY PRODUCTS</h1>
    <!-- DataTales Example -->
    <div id="category">
        <my-products :user="{{ auth()->user()->id }}" />
    </div>
    
    {{-- <script src="/js/sweetalert.min.js"></script> --}}
@endsection