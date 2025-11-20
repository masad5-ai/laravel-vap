<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600">Status: {{ ucfirst($order->status) }}</p>
                    <p class="text-gray-600">Shipping to: {{ $order->shipping_address }}</p>

                    <div class="border rounded divide-y">
                        @foreach ($order->items as $item)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xl font-semibold text-right">Total: ${{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
