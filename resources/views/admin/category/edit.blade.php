@extends('admin.layouts.main')

@section('title', 'Categories')

@section('admin-content')
    <div class="container">
        <h1>{{ __('Edit Category') }}</h1>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </form>
    </div>
@endsection
