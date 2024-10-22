@extends('admin.layouts.main')

@section('title', 'Create Product')

@section('admin-content')
    <div class="wrapper">
        <div class="row no-gutters">
            <div class="col-lg-6">
                <div class="contact-wrap w-100 p-md-5 p-4">
                    <h3>Product Creation</h3>
                    <p class="mb-4">{{ __('Fill in the fields below to create a product') }}</p>
                    <form method="POST" action="{{ route('product.create') }}" class="contactForm mt-1"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name" id="name" placeholder="Product name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
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
                                             style="max-width: 150px; display: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="photos" class="col-form-label">{{ __('Product Photos') }}</label>
                                <div class="form-group">
                                    <input type="file" class="form-control @error('photos') is-invalid @enderror" id="photos" name="photos[]" multiple>
                                    @error('photos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div id="photo-previews" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           name="price" id="price" placeholder="Price">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <textarea name="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              id="description" cols="50" rows="6"
                                              placeholder="Product description"></textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="category_search" class="form-label">{{ __('Search Categories') }}</label>
                                    <input type="text" id="category_search" class="form-control" placeholder="Type to search categories...">
                                    <div id="selected_categories" class="mt-2"></div>
                                    <input type="hidden" name="categories" id="categories">
                                    @error('categories')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <input type="submit" value="Create product" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-stretch">
                <div class="info-wrap w-100 p-5 img img-fluid"
                     style="background-image: url(https://ecs7.tokopedia.net/img/cache/700/product-1/2018/7/18/1552895/1552895_eeb91cc8-5d6b-4b2e-b0b9-16a7e917d0ad_750_727.jpg); background-repeat: no-repeat;">
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(function() {
            $("#category_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/admin-panel/categories/search",
                        method: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name,  // Название категории
                                    value: item.name,  // Значение для отображения
                                    id: item.id        // ID категории
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
        });

        function addCategory(id, name) {
            let selectedCategories = $("#selected_categories");
            let existingBadge = selectedCategories.find(`[data-id="${id}"]`);

            if (existingBadge.length) {
                return; // If category already added, do nothing
            }

            let categoryBadge = $(
                `<span class="badge bg-primary ms-2 text-white" data-id="${id}">
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


        document.getElementById('photos').addEventListener('change', function(event) {
            let photoPreviews = document.getElementById('photo-previews');
            photoPreviews.innerHTML = ''; // Clear existing previews

            let files = event.target.files;

            if (files.length > 15) {
                alert('You can upload up to 15 photos.');
                event.target.value = '';
                return;
            }

            Array.from(files).forEach(file => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail m-2';
                    img.style.maxWidth = '150px';
                    photoPreviews.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
