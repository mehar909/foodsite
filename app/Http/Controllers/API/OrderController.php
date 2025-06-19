<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Customer: Place an order
    public function place(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($request) {
            $order = Order::create([
                'customer_id' => $request->user()->id,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order;
        });

        return response()->json(['message' => 'Order placed', 'order_id' => $order->id], 201);
    }

    // Customer: View their orders
    public function myOrders(Request $request)
    {
        return Order::with('items.menuItem')->where('customer_id', $request->user()->id)->get();
    }

    // Customer: Cancel an order
    public function cancel(Request $request, $id)
    {
    $order = Order::where('customer_id', auth()->id())->findOrFail($id);

    if ($request->status === 'cancelled') {
        $order->status = 'cancelled';
        $order->save();
        return response()->json(['message' => 'Order cancelled successfully']);
    }

    return response()->json(['error' => 'Invalid update'], 400);
}


    // Admin: View all orders
    public function allOrders()
    {
        return Order::with('customer', 'items.menuItem')->orderBy('created_at', 'desc')->get();
    }

    // Admin: Update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Status updated']);
    }
}