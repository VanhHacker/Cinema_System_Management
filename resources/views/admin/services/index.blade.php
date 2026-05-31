@extends('admin.layouts.app')

@section('title', 'Service Management')

@section('content')

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <h2 class="fw-bold">Service Management</h2>
            <p class="text-muted">Manage food & drink services in cinema.</p>
        </div>

        <a href="{{ route('services.create') }}"
           class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-plus"></i> Add New Service
        </a>
    </div>

    <div class="card-box">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table align-middle">
            <thead class="text-muted">
            <tr>
                <th>ID</th>
                <th>SERVICE</th>
                <th>PRICE</th>
                <th>DISCOUNT</th>
                <th width="150">ACTIONS</th>
            </tr>
            </thead>

            <tbody>
            @foreach($services as $s)
                <tr>
                    <td>{{ $s->id }}</td>

                    <td>
                        <strong>{{ $s->name }}</strong><br>
                        <small class="text-muted">{{ $s->description }}</small>
                    </td>

                    <td>{{ number_format($s->price) }} VNĐ</td>

                    <td>{{ $s->discount }}%</td>

                    <td>
                        <a href="{{ route('services.edit', $s->id) }}">
                            <i class="fa-solid fa-pen action-icon edit"></i>
                        </a>

                        <form action="{{ route('services.destroy', $s->id) }}"
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
