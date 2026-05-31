@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

    <div class="mt-4">
        <h2 class="fw-bold">✏️ Edit User</h2>
    </div>

    <div class="card-box">

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method("PUT")

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                       value="{{ $user->username }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       value="{{ $user->email }}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="ADMIN" {{ $user->role=="ADMIN"?"selected":"" }}>ADMIN</option>
                    <option value="MANAGER" {{ $user->role=="MANAGER"?"selected":"" }}>MANAGER</option>
                    <option value="STAFF" {{ $user->role=="STAFF"?"selected":"" }}>STAFF</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Active" {{ $user->status=="Active"?"selected":"" }}>Active</option>
                    <option value="Inactive" {{ $user->status=="Inactive"?"selected":"" }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary rounded-pill px-4">
                    Cancel
                </a>
                <button class="btn btn-primary rounded-pill px-4">
                    Update User
                </button>
            </div>

        </form>

    </div>
@endsection
