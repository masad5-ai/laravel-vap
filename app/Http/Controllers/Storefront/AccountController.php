<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return view('storefront.account.index', [
            'user' => $user,
            'orders' => $user->orders()->latest()->paginate(10),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'marketing_opt_in' => ['nullable', 'boolean'],
            'default_billing_address' => ['nullable', 'array'],
            'default_billing_address.line1' => ['nullable', 'string', 'max:150'],
            'default_billing_address.city' => ['nullable', 'string', 'max:80'],
            'default_billing_address.state' => ['nullable', 'string', 'max:80'],
            'default_billing_address.postcode' => ['nullable', 'string', 'max:10'],
            'default_billing_address.country' => ['nullable', 'string', 'max:80'],
            'default_shipping_address' => ['nullable', 'array'],
            'default_shipping_address.line1' => ['nullable', 'string', 'max:150'],
            'default_shipping_address.city' => ['nullable', 'string', 'max:80'],
            'default_shipping_address.state' => ['nullable', 'string', 'max:80'],
            'default_shipping_address.postcode' => ['nullable', 'string', 'max:10'],
            'default_shipping_address.country' => ['nullable', 'string', 'max:80'],
        ]);

        $user->fill($validated);
        $user->marketing_opt_in = $request->boolean('marketing_opt_in');
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
