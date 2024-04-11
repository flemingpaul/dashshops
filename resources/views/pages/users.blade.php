@extends('dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 5%">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Users List to Approve</div>

                    <div class="card-body">

                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <th>Business name</th>
                                <th>Business Address</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                                <th>Registered at</th>
                                <th></th>
                            </tr>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->business_name }}</td>
                                    <td>{{ $user->business_address }}</td>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.approve', $user->id) }}"
                                           class="btn btn-primary btn-sm">Approve</a>
                                        <a href=""
                                           class="btn btn-danger btn-sm">Reject</a>

                                    </td>
                                    <td>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-success" style="border-radius: 40%">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No users found.</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
