@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Featured Products</h1>
                            <p class="text-sm text-gray-600">Shop curated picks inspired by the demo stores.</p>
                        </div>
                        <a href="{{ route('cart.index') }}" class="text-indigo-600 font-semibold">View Cart</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse ($products as $product)
                            <a href="{{ route('products.show', $product) }}"
                               class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                         class="h-48 w-full object-cover">
                                @else
                                    <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        No image
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                                    <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($product->description, 90) }}</p>
                                    <p class="mt-2 text-indigo-600 font-bold">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-600">No products available yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
