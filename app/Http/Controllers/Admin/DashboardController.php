<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function __invoke()
    {
        $orders = Order::query();
        $monthlyRevenue = $orders->where('created_at', '>=', now()->startOfYear())
            ->selectRaw('DATE_FORMAT(created_at, "%b") as month, SUM(grand_total) as revenue')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->pluck('revenue', 'month');

        return view('admin.dashboard', [
            'metrics' => [
                'revenue' => Order::sum('grand_total'),
                'orders' => Order::count(),
                'customers' => User::whereDoesntHave('roles', function ($query) {
                    $query->whereIn('slug', ['admin', 'super-admin', 'manager', 'product-manager']);
                })->count(),
                'products' => Product::count(),
                'lowStock' => Product::whereColumn('stock', '<=', 'safety_stock')->count(),
            ],
            'recentOrders' => Order::with('items')->latest()->limit(8)->get(),
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}
