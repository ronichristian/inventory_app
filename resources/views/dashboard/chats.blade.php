@extends('layouts.apps')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">CHATS</h1>
    <!-- DataTales Example -->
    <div id="category">
        <chats :user="{{ auth()->user() }}"/>
    </div>
    
    {{-- <script src="/js/sweetalert.min.js"></script> --}}
@endsection