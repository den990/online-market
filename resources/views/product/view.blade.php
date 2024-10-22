@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="col d-flex justify-content-center">
        <div class="card mb-3 col-8">
            <div class="row g-0">
                <div class="col-md-5 mb-2">
                    <div class="image-container position-relative ms-1 mt-1">
                        <img id="main-image" src="{{$product->logo_path}}" class="img-fluid rounded-start w-100" style="height: 300px;" alt="...">
                        <button id="prev-image" class="image-nav-btn prev-btn">&#10094;</button>
                        <button id="next-image" class="image-nav-btn next-btn">&#10095;</button>
                    </div>
                    <div class="image-previews mt-2 d-flex justify-content-center">
                        <img class="preview-image active" src="{{$product->logo_path}}" alt="..."  data-index="0">
                        @foreach($product->photos as $index => $image)
                            <img class="preview-image" src="{{asset('storage/' . $image->file_name)}}" alt="..." data-index="{{$index + 1}}">
                        @endforeach
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card-body h-100">
                        <h5 class="card-title">{{$product->name}}</h5>
                        <div class="d-flex small text-warning mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $product->evaluation)
                                    <i class='fa fa-star' style='color: #f3da35'></i>
                                @else
                                    <i class='fa fa-star' style='color:#858585'></i>
                                @endif
                            @endfor
                        </div>
                        <p class="card-text">{{ $product->description }}</p>
                        <h5 class="fw-bold">Price: {{$product->price}}$</h5>
                        <div class="mb-4 bottom-0">
                            <p class=" mb-1 text-muted small">
                                {{$product->updated_at ? $product->updated_at->format('d M Y') : $product->created_at->format('d M Y')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col d-flex justify-content-center mt-4">
        <div class="col-8">
            <h3>{{ __('Reviews') }}</h3>
            @foreach($product->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $review->user->name }}</h5>
                        <div class="d-flex small text-warning mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class='fa fa-star' style='color: #f3da35'></i>
                                @else
                                    <i class='fa fa-star' style='color:#858585'></i>
                                @endif
                            @endfor
                        </div>
                        <p class="card-text">{{ $review->comment }}</p>
                        <p class="card-text"><small class="text-muted">{{ $review->created_at->format('d M Y') }}</small></p>
                    </div>
                </div>
            @endforeach

            @auth
                <h3>{{ __('Leave a Review') }}</h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="comment">Content</label>
                        <textarea name="comment" id="comment" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <div>Rating</div>
                        <div class="stars">
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                        </div>
                    </div>
                    <input type="hidden" name="rating" id="rating">
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </form>
            @endauth

            @guest
                <p>Please <a href="{{ route('login') }}">login</a> to leave a review.</p>
            @endguest
        </div>
    </div>

    <style>
        .image-container {
            position: relative;
        }
        .image-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px 10px;
        }
        .prev-btn {
            left: 0;
        }
        .next-btn {
            right: 0;
        }
        .image-previews img {
            width: 50px;
            height: 50px;
            margin: 0 5px;
            cursor: pointer;
        }
        .image-previews .active {
            border: 2px solid #000;
        }
        .star {
            display: inline-block;
            vertical-align: top;
            width: 20px;
            height: 20px;
            background: linear-gradient(to bottom, rgba(197, 196, 196, 1) 0%, rgba(180, 179, 178, 1) 100%);
            position: relative;
        }
        .star,
        .star:before {
            -webkit-clip-path: polygon(50% 0%, 66% 27%, 98% 35%, 76% 57%, 79% 91%, 50% 78%, 21% 91%, 24% 57%, 2% 35%, 32% 27%);
            clip-path: polygon(50% 0%, 66% 27%, 98% 35%, 76% 57%, 79% 91%, 50% 78%, 21% 91%, 24% 57%, 2% 35%, 32% 27%);
        }
        .star:hover {
            background: linear-gradient(to bottom, rgba(224, 194, 75, 1) 0%, rgba(207, 125, 0, 1) 100%);
        }
        .star:hover:before {
            background: linear-gradient(to bottom, rgba(243, 212, 85, 1) 0%, rgba(238, 164, 0, 1) 100%);
        }
    </style>

    <script type="module">
        $(document).ready(function() {
            let currentIndex = 0;
            const photos = [
                '{{$product->logo_path}}',
                @foreach($product->photos as $photo)
                    '{{ asset('storage/' . $photo->file_name) }}',
                @endforeach
            ];

            function updateMainImage(index) {
                $('#main-image').attr('src', photos[index]);
                $('.preview-image').removeClass('active');
                $(`.preview-image[data-index=${index}]`).addClass('active');
            }

            $('#prev-image').on('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateMainImage(currentIndex);
                }
            });

            $('#next-image').on('click', function() {
                if (currentIndex < photos.length - 1) {
                    currentIndex++;
                    updateMainImage(currentIndex);
                }
            });

            $('.preview-image').on('click', function() {
                currentIndex = $(this).data('index');
                updateMainImage(currentIndex);
            });

            $(".stars div").on("click", function() {
                var index1 = $(this).index();
                $("#rating").val(index1 + 1);
                $(".stars div").each(function(index2) {
                    if (index1 >= index2) {
                        $(this).css('background',  'linear-gradient(to bottom, rgba(224, 194, 75, 1) 0%, rgba(207, 125, 0, 1) 100%)');
                    } else {
                        $(this).css('background',  'linear-gradient(to bottom, rgba(221, 220, 219, 1) 0%, rgba(209, 208, 206, 1) 100%)');
                    }
                });
            });
        });
    </script>
@endsection
