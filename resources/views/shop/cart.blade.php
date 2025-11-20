<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold">Your Cart</h1>
                        <a href="{{ route('home') }}" class="text-indigo-600">Continue shopping</a>
                    </div>

                    @if (session('status'))
                        <div class="p-3 bg-green-50 text-green-700 rounded">{{ session('status') }}</div>
                    @endif

                    <div class="space-y-4">
                        @forelse ($cartItems as $item)
                            <div class="flex justify-between items-center border-b pb-4">
                                <div>
                                    <p class="font-semibold">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-600">${{ number_format($item->product->price, 2) }} each</p>
                                </div>
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" class="w-20 border-gray-300 rounded">
                                    <x-primary-button>Update</x-primary-button>
                                </form>
                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Remove</x-danger-button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-600">Your cart is empty.</p>
                        @endforelse
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold">Subtotal: ${{ number_format($subtotal, 2) }}</p>
                        @if ($cartItems->isNotEmpty())
                            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="block">
                                    <span class="text-gray-700">Shipping address</span>
                                    <input name="shipping_address" class="mt-1 block w-96 border-gray-300 rounded" required
                                           value="{{ old('shipping_address', auth()->user()->address) }}">
                                </label>
                                <x-primary-button>Checkout</x-primary-button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
