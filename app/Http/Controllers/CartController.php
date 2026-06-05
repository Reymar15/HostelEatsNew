<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cartItems(),
            'total' => $this->cartTotal(),
        ]);
    }

    public function store(Request $request, Food $food): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $cart = session()->get('cart', []);
        $quantity = (int) ($data['quantity'] ?? 1);
        $cart[$food->id] = ($cart[$food->id] ?? 0) + $quantity;

        session(['cart' => $cart]);

        return back()->with('status', "{$food->name} added to cart.");
    }

    public function update(Request $request, Food $food): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $cart = session()->get('cart', []);

        if ((int) $data['quantity'] === 0) {
            unset($cart[$food->id]);
        } else {
            $cart[$food->id] = (int) $data['quantity'];
        }

        session(['cart' => $cart]);

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(Food $food): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$food->id]);
        session(['cart' => $cart]);

        return back()->with('status', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return back()->with('status', 'Cart cleared.');
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

    private function cartTotal(): float
    {
        return (float) $this->cartItems()->sum('line_total');
    }
}
