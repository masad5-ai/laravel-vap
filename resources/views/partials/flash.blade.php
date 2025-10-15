@if(session('success'))
    <div class="bg-emerald-500/10 text-emerald-200 border border-emerald-500/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-rose-500/10 text-rose-200 border border-rose-500/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-sm">
            {{ session('error') }}
        </div>
    </div>
@endif
