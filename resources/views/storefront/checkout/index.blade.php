@extends('layouts.storefront')

@section('title', 'Checkout | Vaperoo Commerce')

@section('content')
    <h1 class="text-3xl font-semibold text-white">Secure checkout</h1>
    <form method="post" action="{{ route('checkout.store') }}" class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        @csrf
        <div class="space-y-8">
            <section class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Contact details</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm text-slate-300">Full name</label>
                        <input type="text" name="customer[name]" value="{{ old('customer.name', $user->name ?? '') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Email</label>
                        <input type="email" name="customer[email]" value="{{ old('customer.email', $user->email ?? '') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Phone</label>
                        <input type="tel" name="customer[phone]" value="{{ old('customer.phone', $user->phone ?? '') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Billing address</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-sm text-slate-300">Address line</label>
                        <input type="text" name="billing[line1]" value="{{ old('billing.line1', data_get($user, 'default_billing_address.line1')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">City</label>
                        <input type="text" name="billing[city]" value="{{ old('billing.city', data_get($user, 'default_billing_address.city')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">State</label>
                        <input type="text" name="billing[state]" value="{{ old('billing.state', data_get($user, 'default_billing_address.state')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Postcode</label>
                        <input type="text" name="billing[postcode]" value="{{ old('billing.postcode', data_get($user, 'default_billing_address.postcode')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Country</label>
                        <input type="text" name="billing[country]" value="{{ old('billing.country', data_get($user, 'default_billing_address.country', 'Australia')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="shipping_same" value="1" {{ old('shipping_same', true) ? 'checked' : '' }} class="rounded border-white/10 bg-slate-900/60" />
                    <span>Shipping address is the same as billing</span>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2" x-data="{ same: {{ old('shipping_same', true) ? 'true' : 'false' }} }">
                    <div class="sm:col-span-2">
                        <label class="text-sm text-slate-300">Shipping line</label>
                        <input type="text" name="shipping[line1]" value="{{ old('shipping.line1', data_get($user, 'default_shipping_address.line1')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">City</label>
                        <input type="text" name="shipping[city]" value="{{ old('shipping.city', data_get($user, 'default_shipping_address.city')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">State</label>
                        <input type="text" name="shipping[state]" value="{{ old('shipping.state', data_get($user, 'default_shipping_address.state')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Postcode</label>
                        <input type="text" name="shipping[postcode]" value="{{ old('shipping.postcode', data_get($user, 'default_shipping_address.postcode')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-slate-300">Country</label>
                        <input type="text" name="shipping[country]" value="{{ old('shipping.country', data_get($user, 'default_shipping_address.country', 'Australia')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Payment method</h2>
                <div class="mt-6 space-y-3 text-sm text-slate-300">
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                        <input type="radio" name="payment_method" value="card" {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }} class="rounded-full border-white/10 bg-slate-900/60" />
                        <span>Visa / Mastercard (Stripe test)</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                        <input type="radio" name="payment_method" value="bank" {{ old('payment_method') === 'bank' ? 'checked' : '' }} class="rounded-full border-white/10 bg-slate-900/60" />
                        <span>Manual bank transfer</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                        <input type="radio" name="payment_method" value="afterpay" {{ old('payment_method') === 'afterpay' ? 'checked' : '' }} class="rounded-full border-white/10 bg-slate-900/60" />
                        <span>Afterpay mock capture</span>
                    </label>
                    <label class="block text-sm text-slate-300">
                        <span>Order notes</span>
                        <textarea name="notes" rows="3" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white">{{ old('notes') }}</textarea>
                    </label>
                </div>
            </section>
        </div>
        <aside class="space-y-6">
            <div class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Order summary</h2>
                <div class="mt-4 space-y-4 text-sm text-slate-300">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between">
                            <span>{{ $item['quantity'] }} × {{ $item['product']->name }}</span>
                            <span>${{ number_format($item['line_total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <dl class="mt-6 space-y-3 text-sm text-slate-300">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>${{ number_format($totals['subtotal'], 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Tax</dt><dd>${{ number_format($totals['tax'], 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Shipping</dt><dd>${{ number_format($totals['shipping'], 2) }}</dd></div>
                    <div class="flex justify-between text-emerald-300"><dt>Discount</dt><dd>- ${{ number_format($totals['discount'], 2) }}</dd></div>
                </dl>
                <div class="mt-6 flex items-center justify-between text-lg font-semibold text-white">
                    <span>Total due</span>
                    <span>${{ number_format($totals['total'], 2) }}</span>
                </div>
                <button class="mt-6 w-full rounded-full bg-cyan-500 px-6 py-3 text-sm font-semibold text-slate-900">Place order</button>
            </div>
        </aside>
    </form>
@endsection
