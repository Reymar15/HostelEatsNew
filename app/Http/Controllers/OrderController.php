<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.food.branch')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function checkout(): View|RedirectResponse
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        return view('orders.checkout', [
            'items' => $items,
            'total' => $items->sum('line_total'),
        ]);
    }

    public function store(): RedirectResponse
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($items) {
            $order = auth()->user()->orders()->create([
                'total' => $items->sum('line_total'),
                'status' => 'Pending',
            ]);

            foreach ($items as $food) {
                $order->items()->create([
                    'food_id' => $food->id,
                    'quantity' => $food->cart_quantity,
                    'price' => $food->price,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('orders.index')
            ->with('status', "Order #{$order->id} placed successfully.");
    }

    private function cartItems()
    {
        $cart = session()->get('cart', []);

        if ($cart === []) {
            return collect();
        }

        return Food::with('branch')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->map(function (Food $food) use ($cart) {
                $food->cart_quantity = $cart[$food->id];
                $food->line_total = $food->price * $food->cart_quantity;

                return $food;
            });
    }
}
