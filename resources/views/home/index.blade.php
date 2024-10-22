@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">{{ __('Online Market') }}</h1>
                <p class="lead fw-normal text-white-50 mb-0">{{ __('Online Market') }}</p>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <form id="search-form" class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search for products" id="query"
                           name="query" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">{{ __('Search') }}</button>
                    <button class="btn btn-outline-danger ms-2" type="button" id="clear-search">{{ __('Clear') }}</button>
                </form>
            </div>
        </div>

        <div class="row row-cols-4 mt-5" id="product-list">
            @include('product.products_list')
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $('#search-form').on('submit', function (event) {
                event.preventDefault();
                performSearch();
            });

            $('#clear-search').on('click', function () {
                $('#query').val('');
                performSearch(true);
            });

            function performSearch(clear = false) {
                let query = $('#query').val();
                if (clear) query = '';

                $.ajax({
                    url: '{{ route('product.search') }}',
                    type: 'GET',
                    data: {query: query},
                    success: function (response) {
                        $('#product-list').html(response);
                    }
                });
            }
        });
    </script>
@endsection
