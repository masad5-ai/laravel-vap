<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredProducts = Product::with('images', 'category')
            ->active()
            ->where('is_featured', true)
            ->limit(8)
            ->get();

        $newArrivals = Product::with('images')
            ->active()
            ->latest()
            ->limit(8)
            ->get();

        $topCategories = Category::with(['products' => fn ($query) => $query->active()->limit(4)])
            ->where('is_visible', true)
            ->limit(6)
            ->get();

        return view('storefront.home', [
            'featuredProducts' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'topCategories' => $topCategories,
            'valueProps' => [
                ['icon' => 'truck', 'title' => 'Express Shipping', 'copy' => 'Same-day dispatch before 2pm AEST.'],
                ['icon' => 'shield-check', 'title' => 'Compliance Ready', 'copy' => 'Australian TGO 110 compliant nic salt options.'],
                ['icon' => 'sparkles', 'title' => 'Curated Flavours', 'copy' => 'Hand-picked blends inspired by top AU vape retailers.'],
            ],
        ]);
    }
}
