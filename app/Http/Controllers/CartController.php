<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItems = CartItem::where('user_id', $user->id)
            ->where('product_id', $data['product_id'])
            ->get();

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                $cartItem->update([
                    'quantity' => DB::raw('quantity + ' . $data['quantity'])
                ]);
            }
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity']
            ]);
        }


        $cartCount = CartItem::where('user_id', $user->id)->count();

        return response()->json(['cartCount' => $cartCount]);
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function remove($id)
    {
        $cartItem = CartItem::find($id);
        $cartItem->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::find($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'price' => $cartItem->product->price
        ]);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
