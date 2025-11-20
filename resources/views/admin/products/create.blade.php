<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Create Product</h1>
                    <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-4">
                        @csrf
                        @include('admin.products.partials.form')
                        <x-primary-button>Create</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
