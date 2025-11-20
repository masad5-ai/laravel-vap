<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold">Products</h1>
                        <a href="{{ route('admin.products.create') }}" class="text-indigo-600">Add Product</a>
                    </div>

                    @if (session('status'))
                        <div class="p-3 bg-green-50 text-green-700 rounded">{{ session('status') }}</div>
                    @endif

                    <div class="border rounded divide-y">
                        @foreach ($products as $product)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">Stock: {{ $product->stock }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('products.show', $product) }}" class="text-indigo-600">View</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-gray-700">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button>Delete</x-danger-button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
