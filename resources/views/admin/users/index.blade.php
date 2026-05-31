@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <h2 class="fw-bold">User Management</h2>
            <p class="text-muted">Manage your staff and administration team access.</p>
        </div>

        <a href="{{ route('users.create') }}"
           class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-user-plus"></i> Add New User
        </a>
    </div>

    <div class="card-box">

        <table class="table align-middle">
            <thead class="text-muted">
            <tr>
                <th>USER</th>
                <th>ROLE</th>
                <th>STATUS</th>
                <th width="150">ACTIONS</th>
            </tr>
            </thead>

            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>
                        <strong>{{ $u->username }}</strong><br>
                        <small class="text-muted">{{ $u->email }}</small>
                    </td>

                    <td>
                        @if($u->role == "ADMIN")
                            <span class="badge bg-primary">ADMIN</span>
                        @elseif($u->role == "MANAGER")
                            <span class="badge bg-warning text-dark">MANAGER</span>
                        @else
                            <span class="badge bg-secondary">STAFF</span>
                        @endif
                    </td>

                    <td>
                        @if($u->status == "Active")
                            <span class="text-success">● Active</span>
                        @else
                            <span class="text-muted">● Inactive</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('users.edit', $u->id) }}">
                            <i class="fa-solid fa-pen action-icon edit"></i>
                        </a>

                        <form action="{{ route('users.destroy', $u->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method("DELETE")
                            <button style="border:none;background:none;">
                                <i class="fa-solid fa-trash action-icon delete"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection
