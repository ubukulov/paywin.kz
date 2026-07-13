<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PartnerGiftService;

class ProductController extends BaseController
{
    protected PartnerGiftService  $partnerGiftService;

    public function __construct(PartnerGiftService $partnerGiftService)
    {
        $this->partnerGiftService = $partnerGiftService;
    }

    public function show($slug)
    {
        $product = Product::with('images')
            ->select('products.*', 'product_stocks.quantity', 'product_stocks.price', 'product_stocks.is_preorder', 'product_stocks.delivery_days')
            ->join('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            ->whereSlug($slug)
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        $gifts = $this->partnerGiftService->getEligiblePrizesForProduct();

        $platformPromotions = \App\Models\Promotion::where('is_active', true)
            ->where('type', 'purchase') // Акции за покупку
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->get();

        return view('category.product', compact('product', 'gifts', 'platformPromotions'));
    }
}
