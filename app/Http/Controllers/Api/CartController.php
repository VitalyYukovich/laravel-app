<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $owner = $this->getOwner();
        return CartItem::with('item')->where($owner)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:product,service',
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $owner = $this->getOwner();
        $model = $request->type === 'product' ? Product::class : Service::class;
        
        $cartItem = CartItem::updateOrCreate(
            array_merge($owner, [
                'item_id' => $request->id,
                'item_type' => $model,
            ]),
            ['quantity' => $request->quantity]
        );

        return response()->json($cartItem->load('item'), 201);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);

        $owner = $this->getOwner();
        
        if (($cartItem->user_id && $cartItem->user_id !== ($owner['user_id'] ?? null)) ||
            ($cartItem->session_id && $cartItem->session_id !== ($owner['session_id'] ?? null))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $cartItem->delete();
        return response()->json(['message' => 'Item removed']);
    }

    private function getOwner()
    {
        return Auth::check()
            ? ['user_id' => Auth::id()]
            : ['session_id' => Session::getId()];
    }
}