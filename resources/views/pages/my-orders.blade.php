@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="auth-toast order-success-toast">Order placed successfully!</div>
    @endif
    @if (session('error'))
        <div class="auth-toast error">{{ session('error') }}</div>
    @endif
    @if (session('open_checkout'))
        <span data-auto-checkout hidden></span>
    @endif

    <section class="order-layout">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>My Cart</h2>
                    <p>Items you added from the menu. Totals update instantly.</p>
                </div>
                <button type="button" class="ghost-button" data-clear-cart>Clear</button>
            </div>

            <div class="cart-list" data-cart-list></div>
            <p class="empty-cart-message" data-empty-cart>Your cart is empty. Add food from any branch menu.</p>

            <dl class="summary" data-cart-summary hidden>
                <div><dt>Subtotal</dt><dd>PHP<span data-cart-subtotal>0.00</span></dd></div>
                <div><dt>Delivery Fee</dt><dd>PHP<span data-cart-delivery>15.00</span></dd></div>
                <div class="total"><dt>Grand Total</dt><dd>PHP<span data-cart-total>0.00</span></dd></div>
            </dl>

            <button type="button" class="primary-action checkout-open-button" data-open-checkout disabled>Proceed to Checkout</button>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>My Orders</h2>
                    <p>Session orders placed from checkout with live admin status updates.</p>
                </div>
            </div>

            <div class="status-list">
                @forelse ($activeOrders as $order)
                    @php($status = strtolower($order['delivery_status'] ?? $order['status'] ?? 'pending'))
                    <div class="status-row" data-searchable="{{ strtolower(($order['order_number'] ?? '').' '.($order['foods'] ?? '').' '.($order['delivery_status'] ?? '')) }}">
                        <span>{{ $order['order_number'] }}</span>
                        <h3>{{ $order['foods'] }}</h3>
                        <p><span class="status-pill {{ $status }}">{{ $order['delivery_status'] }}</span> {{ \Illuminate\Support\Carbon::parse($order['created_at'])->format('M d, Y h:i A') }}</p>
                        <strong>PHP{{ number_format($order['total'], 2) }}</strong>
                    </div>
                @empty
                    <p class="empty-cart-message">No active orders yet. Place an order and it will appear here.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
