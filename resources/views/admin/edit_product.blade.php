@extends('admin.layouts.main')

@section('title', 'Edit Product')

@section('admin-content')
    <div class="col-6 mt-5 ms-2">
        <h3>{{ __('Edit Product') }}</h3>
        <form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group fw-semibold d-flex flex-column">
                <label for="img_id" class="col-md-4 col-form-label">{{ __('Logo Product') }}</label>
                <div class="col-md-6">
                    <input class="form-control @error('img_id') is-invalid @enderror" type="file"
                           id="img_id" name="img_id" autocomplete="img_id" autofocus
                           onchange="previewAvatar()">
                    @error('img_id')
                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                    @enderror
                    <div class="mt-2">
                        <img id="preview-avatar" class="img-thumbnail"
                             style="max-width: 150px;" src="{{$product->logo_path}}">
                    </div>
                </div>
            </div>
            <div class="form-group fw-semibold">
                <label for="name">{{ __('Product Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>
            <div class="form-group fw-semibold">
                <label for="price">{{ __('Price') }}</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>
            <div class="form-group fw-semibold">
                <label for="description">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $product->description }}</textarea>
            </div>

            <div class="form-group fw-semibold">
                <label for="category_search" class="form-label">{{ __('Search Categories') }}</label>
                <input type="text" id="category_search" class="form-control" placeholder="Type to search categories...">
                <div id="selected_categories" class="mt-2">
                    @foreach($product->categories as $category)
                        <span class="badge bg-primary mr-2 text-white" data-id="{{ $category->id }}">
                            {{ $category->name }}
                            <button class="btn btn-sm btn-danger px-1 py-0">x</button>
                        </span>
                    @endforeach
                </div>
                <input type="hidden" name="categories" id="categories" value="{{ $product->categories->pluck('id')->join(',') }}">
                @error('categories')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">{{ __('Update Product') }}</button>
        </form>
    </div>

    <script type="module">
        $(function() {
            $("#category_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/admin-panel/categories/search",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name,
                                    value: item.name,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    addCategory(ui.item.id, ui.item.value);
                    $("#category_search").val('');
                    return false;
                }
            });

            $("#selected_categories").on('click', 'button', function() {
                $(this).parent().remove();
                updateCategoryInput();
            });
        });

        function addCategory(id, name) {
            let selectedCategories = $("#selected_categories");
            let existingBadge = selectedCategories.find(`[data-id="${id}"]`);

            if (existingBadge.length) {
                return;
            }

            let categoryBadge = $(
                `<span class="badge bg-primary mr-2 text-white" data-id="${id}">
                    ${name}
                    <button class="btn btn-sm btn-danger px-1 py-0">x</button>
                </span>`
            );

            categoryBadge.find('button').on('click', function() {
                $(this).parent().remove();
                updateCategoryInput();
            });

            selectedCategories.append(categoryBadge);
            updateCategoryInput();
        }

        function updateCategoryInput() {
            let categoryIds = $("#selected_categories .badge").map(function() {
                return $(this).data('id');
            }).get();
            $("#categories").val(categoryIds.join(','));
        }
    </script>
@endsection
