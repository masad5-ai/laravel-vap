@extends('layouts.storefront')

@section('title', 'My account | Vaperoo Commerce')

@section('content')
    <h1 class="text-3xl font-semibold text-white">Account overview</h1>
    <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <section class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
            <h2 class="text-xl font-semibold text-white">Profile</h2>
            <form method="post" action="{{ route('account.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('put')
                <div>
                    <label class="text-sm text-slate-300">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                </div>
                <div>
                    <label class="text-sm text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                </div>
                <div>
                    <label class="text-sm text-slate-300">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="marketing_opt_in" value="1" {{ old('marketing_opt_in', $user->marketing_opt_in) ? 'checked' : '' }} class="rounded border-white/10 bg-slate-900/60" />
                    <span>Receive product drops and compliance bulletins</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm text-slate-300">Default billing</label>
                        <textarea name="default_billing_address[line1]" rows="3" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white">{{ old('default_billing_address.line1', data_get($user, 'default_billing_address.line1')) }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Default shipping</label>
                        <textarea name="default_shipping_address[line1]" rows="3" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white">{{ old('default_shipping_address.line1', data_get($user, 'default_shipping_address.line1')) }}</textarea>
                    </div>
                </div>
                <button class="rounded-full bg-cyan-500 px-6 py-3 text-sm font-semibold text-slate-900">Update profile</button>
            </form>
        </section>
        <section class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
            <h2 class="text-xl font-semibold text-white">Recent orders</h2>
            <div class="mt-4 space-y-4 text-sm text-slate-300">
                @foreach($orders as $order)
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-white">{{ $order->order_number }}</span>
                            <span class="text-xs uppercase tracking-widest text-cyan-300">{{ $order->status }}</span>
                        </div>
                        <p class="mt-2 text-xs text-slate-400">Placed {{ $order->placed_at?->diffForHumans() ?? $order->created_at->diffForHumans() }}</p>
                        <p class="mt-2 text-sm text-white">${{ number_format($order->grand_total, 2) }}</p>
                        <a href="{{ route('admin.orders.show', $order) }}" class="mt-3 inline-flex items-center text-xs uppercase tracking-wide text-cyan-300">View order</a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </section>
    </div>
@endsection
