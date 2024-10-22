@extends('admin.layouts.main')

@section('title', 'Categories')

@section('admin-content')
    <div class="container">
        <h1>{{ __('Categories') }}</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Add Category</a>

        <table class="table">
            <thead>
            <tr>
                <th class="col-2">{{ __('ID') }}</th>
                <th class="col-8">{{ __('Name') }}</th>
                <th class="col-2">{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
