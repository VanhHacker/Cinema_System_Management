@extends('admin.layouts.app')

@section('title', 'Edit Service')

@section('content')

    <div class="mt-4">
        <h2 class="fw-bold">✏️ Edit Service</h2>
        <p class="text-muted">Update cinema service information.</p>
    </div>

    <div class="card-box">

        <form action="{{ route('services.update', $service->id) }}"
              method="POST">
            @csrf
            @method("PUT")

            <div class="mb-3">
                <label class="form-label">Service Name</label>
                <input type="text" name="name"
                       value="{{ $service->name }}"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description"
                          class="form-control">{{ $service->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (VNĐ)</label>
                <input type="number" name="price"
                       value="{{ $service->price }}"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" name="discount"
                       value="{{ $service->discount }}"
                       class="form-control">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.index') }}"
                   class="btn btn-secondary rounded-pill px-4">
                    Cancel
                </a>

                <button class="btn btn-primary rounded-pill px-4">
                    Update Service
                </button>
            </div>

        </form>

    </div>
@endsection
