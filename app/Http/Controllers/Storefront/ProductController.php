<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images', 'category')->active();

        if ($search = $request->string('q')->trim()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->string('category')->trim()) {
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $categorySlug));
        }

        if ($minPrice = $request->float('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->float('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        $sort = $request->string('sort')->lower();

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            'featured' => $query->orderByDesc('is_featured'),
            default => $query->orderBy('name'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_visible', true)->orderBy('name')->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'q' => $search,
                'category' => $categorySlug,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'sort' => $sort,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with('images', 'category')->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with('images')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('storefront.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
