<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        $hasOrdered = $user->orders()->whereHas('products', function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->exists();

        if (!$hasOrdered) {
            return redirect()->back()->withErrors('You can only review products you have purchased.');
        }

        $request->validate([
            'comment' => 'required',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = new Review();
        $review->user_id = $user->id;
        $review->product_id = $product->id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }
}
