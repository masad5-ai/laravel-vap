<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load(['items.product']);

        return view('shop.order', compact('order'));
    }
}
