<?php

namespace App\Http\Controllers;

use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.layouts.main');
    }

    public function createProduct()
    {
        return view('admin.create_product');
    }

    public function products()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }
}
