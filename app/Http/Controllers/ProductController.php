<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with('images')
                ->whereSlug($slug)
                ->join('product_stocks', 'product_stocks.product_id', '=', 'products.id')
                ->select('products.*', 'product_stocks.quantity', 'product_stocks.price')
                ->firstOrFail();

        return view('category.product', compact('product'));
    }
}
