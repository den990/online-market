@foreach($products as $product)
    <div class="col-3 mb-5 px-3">
        <div class="card h-100">
            <img class="card-img-top" src="{{$product->logo_path}}" alt="..."/>
            <div class="card-body p-4">
                <div class="text-center">
                    <h5 class="fw-bolder">
                        <a href="{{ route('product.view', $product->id) }}">
                            {{ $product->name }}
                        </a>
                    </h5>
                    <div class="d-flex justify-content-center small text-warning mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->evaluation)
                                <i class='fa fa-star' style='color: #f3da35'></i>
                            @else
                                <i class='fa fa-star' style='color:#858585'></i>
                            @endif
                        @endfor
                    </div>
                    {{$product->price}}$
                </div>
            </div>
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center">
                    @if (\Illuminate\Support\Facades\Auth::check())
                        @if(Request::is('admin-panel/products'))
                            <a class="btn btn-outline-primary mt-auto" href="{{ route('product.edit', $product->id) }}">Edit</a>
                        @else
                            <button type="button" class="btn btn-outline-dark mt-auto add-to-cart" data-product-id="{{ $product->id }}">{{ __('Add to cart') }}</button>
                        @endif
                    @else
                        <a class="btn btn-outline-dark mt-auto" href="{{route('login')}}">{{ __('Add to cart') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

<script type="module">
    $(document).ready(function () {
        $('.add-to-cart').on('click', function (e) {

            var button = $(this);
            var productId = button.data('product-id');

            $.ajax({
                url: 'http://127.0.0.1:8000/cart/add',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_id: productId,
                    quantity: 1,
                },
                success: function (response) {
                    $('#cart-count').text(response.cartCount);
                    alert('Product added to cart!');
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Failed to add product to cart.');
                }
            });
        });
    });
</script>
