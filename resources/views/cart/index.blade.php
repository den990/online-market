@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3>Your Cart</h3>
                @if ($cartItems->isEmpty())
                    <p>{{ __('Your cart is empty') }}</p>
                @else
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody id="cart-items">
                        @foreach ($cartItems as $item)
                            <tr data-id="{{ $item->id }}">
                                <td><a href="{{ route('product.view', $item->product->id) }}">{{ $item->product->name }}</a></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary update-quantity" data-action="decrement">-</button>
                                    <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1" readonly>
                                    <button class="btn btn-sm btn-secondary update-quantity" data-action="increment">+</button>
                                </td>
                                <td>${{ $item->product->price }}</td>
                                <td class="item-total">${{ $item->product->price * $item->quantity }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item">{{ __('Remove') }}</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        <h4>Total: $<span id="cart-total">{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}</span></h4>
                        <button class="btn btn-primary" id="checkout-btn">{{ __('Checkout') }}</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {
            function updateCartTotal() {
                let total = 0;
                $('.item-total').each(function () {
                    total += parseFloat($(this).text().substring(1));
                });
                $('#cart-total').text(total.toFixed(2));
            }

            $('.update-quantity').click(function () {
                const row = $(this).closest('tr');
                const quantityInput = row.find('.quantity-input');
                let quantity = parseInt(quantityInput.val());
                const action = $(this).data('action');

                if (action === 'increment') {
                    quantity++;
                } else if (action === 'decrement' && quantity > 1) {
                    quantity--;
                }

                quantityInput.val(quantity);

                const itemId = row.data('id');
                $.ajax({
                    url: `/cart/update/${itemId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: quantity
                    },
                    success: function (data) {
                        if (data.success) {
                            const itemTotal = row.find('.item-total');
                            itemTotal.text(`$${(data.price * quantity).toFixed(2)}`);
                            updateCartTotal();
                        }
                    }
                });
            });

            $('.remove-item').click(function () {
                const row = $(this).closest('tr');
                const itemId = row.data('id');

                $.ajax({
                    url: `/cart/remove/${itemId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success) {
                            row.remove();
                            updateCartTotal();
                        }
                    }
                });
            });

            $('#checkout-btn').click(function () {
                $.ajax({
                    url: `{{route('order.create')}}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success) {
                            window.location.href = `/orders`;
                        }
                    }
                });
            });
        });
    </script>

@endsection
