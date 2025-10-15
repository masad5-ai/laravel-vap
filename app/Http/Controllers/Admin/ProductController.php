<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $product = DB::transaction(function () use ($data) {
            $images = collect($data['images'] ?? []);
            unset($data['images']);

            $product = Product::create($data);

            $this->syncImages($product, $images);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product);

        DB::transaction(function () use ($product, $data) {
            $images = collect($data['images'] ?? []);
            unset($data['images']);

            $product->update($data);

            $this->syncImages($product, $images);
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    protected function validateProduct(Request $request, ?Product $product = null): array
    {
        $id = $product?->id;

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:160', 'unique:products,slug,'.($id ?? 'NULL').',id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,'.($id ?? 'NULL').',id'],
            'brand' => ['nullable', 'string', 'max:120'],
            'hero_image' => ['nullable', 'url'],
            'thumbnail' => ['nullable', 'url'],
            'nicotine_strength' => ['nullable', 'string', 'max:50'],
            'flavour_profile' => ['nullable', 'string', 'max:80'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'attributes' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
            'images' => ['nullable', 'array'],
            'images.*.url' => ['nullable', 'url'],
            'images.*.alt' => ['nullable', 'string', 'max:120'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['attributes'] = $data['attributes'] ?? [];
        $data['metadata'] = $data['metadata'] ?? [];

        return $data;
    }

    protected function syncImages(Product $product, $images): void
    {
        if ($images === null) {
            return;
        }

        $product->images()->delete();

        $order = 1;
        foreach ($images as $image) {
            if (empty($image['url'])) {
                continue;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $image['url'],
                'alt_text' => $image['alt'] ?? null,
                'is_primary' => $order === 1,
                'sort_order' => $order++,
            ]);
        }
    }
}
