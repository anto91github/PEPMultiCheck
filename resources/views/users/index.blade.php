@extends('layouts.app')

@section('title')
User List
@endsection

@section('content')
<div class="bg-light rounded">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Users</h5>
            <h6 class="card-subtitle mb-2 text-muted">Manage your users here.</h6>

            <div class="mt-2">
                @include('layouts.includes.messages')
            </div>

            <div class="mb-2 text-end">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right">Add user</a>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="1%">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col" width="1%" colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $users->firstItem() + $loop->index }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->status == 1)
                                <span style="background-color: #c9ffdc !important; padding: 4px 8px; border-radius: 5px; color: #11bc74 !important;"> 
                                    Aktif 
                                </span>
                            @else
                                <span style="background-color:rgb(243, 170, 165) !important; padding: 4px 8px; border-radius: 5px; color:rgb(245, 3, 3) !important;"> 
                                    Non Aktif 
                                </span>
                            @endif                            
                        </td>
                        <!-- <td><a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm">Show</a></td> -->
                        <td><a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a></td>
                        <td>
                            <!-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex">
                {!! $users->links() !!}
            </div>

        </div>
    </div>
</div>
@endsection
