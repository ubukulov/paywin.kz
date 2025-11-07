<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with('images')->whereSlug($slug)->firstOrFail();

        return view('category.product', compact('product'));
    }
}
