@extends('layouts.apps')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">CATEGORIES</h1>
    <!-- DataTales Example -->
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Email <Address></Address></th>
            <th scope="col">Role</th>
            <th scope="col">Handle</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(',', $user->roles()->pluck('name')->toArray()) }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <script src="/js/sweetalert.min.js"></script>
@endsection