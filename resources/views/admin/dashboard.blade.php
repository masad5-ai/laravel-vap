<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <h1 class="text-2xl font-bold">Admin Dashboard</h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 border rounded">
                            <p class="text-sm text-gray-600">Users</p>
                            <p class="text-2xl font-semibold">{{ $stats['users'] }}</p>
                        </div>
                        <div class="p-4 border rounded">
                            <p class="text-sm text-gray-600">Orders</p>
                            <p class="text-2xl font-semibold">{{ $stats['orders'] }}</p>
                        </div>
                        <div class="p-4 border rounded">
                            <p class="text-sm text-gray-600">Products</p>
                            <p class="text-2xl font-semibold">{{ $stats['products'] }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold">Recent Orders</h2>
                            <a href="{{ route('admin.products.index') }}" class="text-indigo-600">Manage Products</a>
                        </div>
                        <div class="border rounded divide-y">
                            @forelse ($latestOrders as $order)
                                <div class="p-4 flex justify-between">
                                    <div>
                                        <p class="font-semibold">Order #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                    </div>
                                    <p class="font-semibold">${{ number_format($order->total, 2) }}</p>
                                </div>
                            @empty
                                <p class="p-4 text-gray-600">No orders yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
