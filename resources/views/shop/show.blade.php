<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded shadow-md">
                        @else
                            <div class="h-80 bg-gray-100 flex items-center justify-center text-gray-400 rounded">
                                No image
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-3">{{ $product->name }}</h1>
                        <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                        <p class="text-2xl font-semibold text-indigo-600 mb-6">${{ number_format($product->price, 2) }}</p>

                        @auth
                            <form action="{{ route('cart.store', $product) }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="block">
                                    <span class="text-gray-700">Quantity</span>
                                    <input type="number" name="quantity" value="1" min="1" class="mt-1 block w-24 border-gray-300 rounded">
                                </label>
                                <x-primary-button>Add to Cart</x-primary-button>
                            </form>
                        @else
                            <p class="text-sm text-gray-600">Please <a href="{{ route('login') }}" class="text-indigo-600">login</a> to purchase.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
