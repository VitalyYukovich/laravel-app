<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    private function getOwner()
    {
        return Auth::check()
            ? ['user_id' => Auth::id()]
            : ['session_id' => Session::getId()];
    }

    public function store()
    {
        $owner = $this->getOwner();

        $cartItems = CartItem::with('item')->where($owner)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $total = $cartItems->sum(fn ($item) => $item->item->price * $item->quantity);

        $order = Order::create(array_merge($owner, [
            'status' => 'pending',
            'total_price' => $total
        ]));

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'item_type' => $cartItem->item_type,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->item->price,
            ]);
        }

        // очистка корзины
        CartItem::where($owner)->delete();

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
        ]);
    }

    public function index()
    {
        $owner = $this->getOwner();

        return Order::with('items.item')->where($owner)->get();
    }
}
