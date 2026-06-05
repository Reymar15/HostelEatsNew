<div class="cart-drawer-backdrop" data-close-cart-sidebar></div>
<aside class="cart-drawer" aria-label="Cart sidebar">
    <header>
        <div>
            <p class="crumb">Cart</p>
            <h2>Hostel basket</h2>
        </div>
        <button type="button" class="secondary-button icon-button" data-close-cart-sidebar aria-label="Close cart">X</button>
    </header>

    <div class="cart-list drawer-cart-list" data-cart-list></div>

    <p class="empty-cart-message" data-empty-cart>Your cart is empty. Add food from any branch menu.</p>

    <dl class="summary drawer-summary" data-cart-summary hidden>
        <div><dt>Subtotal</dt><dd>PHP<span data-cart-subtotal>0.00</span></dd></div>
        <div><dt>Delivery Fee</dt><dd>PHP<span data-cart-delivery>15.00</span></dd></div>
        <div class="total"><dt>Grand Total</dt><dd>PHP<span data-cart-total>0.00</span></dd></div>
    </dl>

    <button type="button" class="primary-action checkout-open-button" data-open-checkout disabled>Proceed to Checkout</button>
</aside>

<div class="checkout-modal" data-checkout-modal aria-hidden="true">
    <form class="checkout-dialog" method="POST" action="{{ route('orders.store') }}">
        @csrf
        <header>
            <div>
                <p class="crumb">Checkout</p>
                <h2>Complete your order</h2>
            </div>
            <button type="button" class="secondary-button icon-button" data-close-checkout aria-label="Close checkout">X</button>
        </header>

        <div class="checkout-grid">
            <label>
                Full Name
                <input type="text" name="full_name" value="{{ $profile['name'] ?? session('auth_name') }}" required>
            </label>
            <label>
                Hostel Block
                <input type="text" name="hostel_block" value="{{ $profile['hostel_block'] ?? session('auth_hostel_block') }}" required>
            </label>
            <label>
                Room Number
                <input type="text" name="room_number" placeholder="Room 214" required>
            </label>
            <label>
                Contact Number
                <input type="text" name="contact_number" placeholder="09xxxxxxxxx" required>
            </label>
        </div>

        <section class="checkout-summary">
            <h3>Order summary</h3>
            <div data-checkout-items></div>
            <strong>Total amount: PHP<span data-checkout-total>0.00</span></strong>
        </section>

        <label class="cod-option">
            <input type="radio" name="payment_method" value="cash_on_delivery" checked>
            <span>Cash on Delivery</span>
        </label>

        <button type="submit" class="primary-action">Place Order</button>
    </form>
</div>
