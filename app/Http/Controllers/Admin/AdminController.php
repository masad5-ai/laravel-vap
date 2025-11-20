<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'users' => User::count(),
            'orders' => Order::count(),
            'products' => Product::count(),
        ];

        $latestOrders = Order::with(['user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}
