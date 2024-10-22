@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center flex-column align-items-center">
    @foreach ($orders as $order)
        <div class="card mb-3 col-10">
            <div class="card-header">
                Order #{{ $order->id }}
                @if($order->status_id == 1):
                    <span class="text-warning">{{ __('Processing') }}</span>
                @else
                    @if($order->status_id == 2):
                        <span class="text-success">{{ __('Complete') }}</span>
                    @else
                        <span class="text-danger">{{ __('Cancel') }}</span>
                    @endif
                @endif
            </div>
            <div class="card-body">
                <p><strong>{{ __('Total Price:') }}</strong> ${{ $order->total_price }}</p>
                <p><strong>{{ __('Created:') }}</strong> {{ $order->created_at }}</p>

                <h5>{{ __('Products') }}</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('№') }}</th>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Quantity') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($order->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    </div>
@endsection
