@php
    use Illuminate\Support\Facades\Auth;
@endphp
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

            <form action="{{ route('users.index') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="pencarian" id="searchInput" placeholder="Keyword" value="{{ request()->input('pencarian') }}">
                            <button class="input-group-text btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>                
            </form>

            <div class="mt-2">
                @include('layouts.includes.messages')
            </div>

            @if (Auth::check() && Auth::user()->email === 'super@admin.com')
            <div class="mb-2 text-end">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right">Add user</a>
            </div>
            @endif
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
                        <td>
                        @if (Auth::check() && Auth::user()->email === 'super@admin.com')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a>
                        @endif
                        </td>
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
