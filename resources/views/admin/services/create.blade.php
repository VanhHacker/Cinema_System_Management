@extends('admin.layouts.app')

@section('title', 'Add Service')

@section('content')

    <div class="mt-4">
        <h2 class="fw-bold">➕ Add New Service</h2>
        <p class="text-muted">Create a new cinema service.</p>
    </div>

    <div class="card-box">

        <form action="{{ route('services.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Service Name</label>
                <input type="text" name="name"
                       class="form-control"
                       placeholder="Enter service name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description"
                          class="form-control"
                          placeholder="Enter description"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (VNĐ)</label>
                <input type="number" name="price"
                       class="form-control"
                       placeholder="Enter price" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" name="discount"
                       class="form-control"
                       placeholder="0 - 100">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.index') }}"
                   class="btn btn-secondary rounded-pill px-4">
                    Cancel
                </a>

                <button class="btn btn-success rounded-pill px-4">
                    Save Service
                </button>
            </div>

        </form>

    </div>
@endsection
