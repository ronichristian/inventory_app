@extends('layouts.apps')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">CATEGORIES</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Mange Users
            </h6>
            <form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="POST">
                @csrf
                {{ method_field('PUT') }}
                @foreach ($roles as $role)
                    <div class="form-check">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                        {{ $user->hasAnyRole($role->name)?'checked':''}}>
                        <label for="">{{ $role->name }}</label>
                    </div>
                @endforeach 
                <button type="submit" class="btn btn-info">Update</button>
            </form>
        </div>
    </div>
    
    <script src="/js/sweetalert.min.js"></script>
@endsection