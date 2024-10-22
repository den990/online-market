@extends('admin.layouts.main')

@section('title', 'Categories')

@section('admin-content')
    <div class="container">
        <h1>{{ __('Add category') }}</h1>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
@endsection
