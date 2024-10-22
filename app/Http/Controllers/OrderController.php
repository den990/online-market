<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                        ->with('products')
                        ->get();
        return view('order.index', compact('orders'));
    }

    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->get();

        $order = new Order();
        $order->user_id = $user->id;
        $order->total_price = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $order->status_id = 1;
        $order->save();

        foreach ($cartItems as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity
            ]);
        }

        CartItem::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'order_id' => $order->id
        ]);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
