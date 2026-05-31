@extends('admin.layouts.app')

@section('content')

    <div class="card-box mt-4">
        <h4>✏️ Sửa Category</h4>

        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
            @csrf
            @method('PUT')

            <input type="text" name="name"
                   value="{{ $category->name }}"
                   class="form-control mb-3" required>

            <textarea name="description"
                      class="form-control mb-3">{{ $category->description }}</textarea>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>

        </form>
    </div>

@endsection
