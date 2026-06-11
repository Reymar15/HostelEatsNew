<section class="panel admin-table-gap" id="orders-table">
    <div class="panel-head">
        <div>
            <h2>Customer Orders</h2>
            <p>All orders across all branches. Super Admin can edit, update status, and delete.</p>
        </div>
        <div class="hero-actions">
            <a class="secondary-action" href="{{ route('admin.orders.index') }}">Full Order Manager</a>
            <button type="button" class="secondary-action" data-sort-table="orders-table">Sort by date</button>
        </div>
    </div>

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search order ID, food, customer..." data-admin-search="orders-table">
        <select data-admin-filter="orders-table" data-filter-key="status">
            <option value="">All statuses</option>
            @foreach (['Pending','Preparing','Ready for Pickup','Completed','Cancelled'] as $s)
                <option value="{{ strtolower(str_replace(' ', '-', $s)) }}">{{ $s }}</option>
            @endforeach
        </select>
        <select data-admin-filter="orders-table" data-filter-key="branch">
            <option value="">All branches</option>
            @foreach (['Jollibee', "McDonald's", 'Mang Inasal', 'KFC', 'Starbucks'] as $b)
                <option value="{{ strtolower($b) }}">{{ $b }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-wrap">
        <table class="history-table admin-data-table" id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Branch</th>
                    <th>Food Item</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adminOrders as $order)
                    @php
                        $status = strtolower(str_replace(' ', '-', $order['delivery_status'] ?? $order['status'] ?? 'pending'));
                        $branch = strtolower($order['branch'] ?? '');
                    @endphp
                    <tr
                        data-searchable="{{ strtolower(($order['order_number'] ?? $order['id'] ?? '').' '.($order['foods'] ?? '').' '.data_get($order, 'customer.full_name', '')) }}"
                        data-status="{{ $status }}"
                        data-branch="{{ $branch }}"
                        data-date="{{ strtotime($order['created_at'] ?? 'now') }}">
                        <td><strong>{{ $order['order_number'] ?? $order['id'] }}</strong></td>
                        <td>
                            <strong>{{ data_get($order, 'customer.full_name', 'Hostel Guest') }}</strong>
                            <small>{{ data_get($order, 'customer.hostel_block', '') }} {{ data_get($order, 'customer.room_number', '') }}</small>
                        </td>
                        <td>{{ $order['branch'] ?? '—' }}</td>
                        <td>{{ $order['foods'] ?? '—' }}</td>
                        <td>{{ collect($order['items'] ?? [])->sum('qty') ?: 1 }}</td>
                        <td>PHP{{ number_format((float) ($order['total'] ?? 0), 2) }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($order['created_at'] ?? now())->format('M d, Y h:i A') }}</td>
                        <td>
                            <form method="POST"
                                action="{{ route('admin.orders.status.session', $order['id'] ?? $order['order_number']) }}"
                                class="inline-status-form">
                                @csrf @method('PATCH')
                                <select class="status-select {{ $status }}" name="delivery_status" onchange="this.form.submit()">
                                    @foreach (['Pending','Preparing','Ready for Pickup','Completed','Cancelled'] as $opt)
                                        <option value="{{ $opt }}" {{ strtolower($order['delivery_status'] ?? '') === strtolower($opt) ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="secondary-button"
                                    data-admin-modal="super-edit-order-modal"
                                    data-order-id="{{ $order['id'] ?? $order['order_number'] }}"
                                    data-customer="{{ data_get($order, 'customer.full_name', '') }}"
                                    data-food="{{ $order['foods'] ?? '' }}"
                                    data-qty="{{ collect($order['items'] ?? [])->sum('qty') ?: 1 }}"
                                    data-total="{{ $order['total'] ?? 0 }}"
                                    data-notes="">
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{-- Super Admin Edit Order Modal --}}
<div class="admin-modal" data-modal-id="super-edit-order-modal" aria-hidden="true">
    <div class="admin-modal-card">
        <header>
            <div>
                <p class="crumb">Super Admin</p>
                <h2>Edit Customer Order</h2>
            </div>
            <button type="button" class="secondary-button icon-button" data-close-admin-modal>✕</button>
        </header>
        <form id="super-edit-order-form" method="POST" action="" class="admin-form-grid">
            @csrf
            <input type="hidden" name="_method" value="PATCH">

            <label style="grid-column:1/-1">
                Customer Name
                <input type="text" name="customer_name" id="sup-edit-customer" required maxlength="120">
            </label>
            <label style="grid-column:1/-1">
                Food Item
                <input type="text" name="food_item" id="sup-edit-food" required maxlength="120">
            </label>
            <label>
                Quantity
                <input type="number" name="quantity" id="sup-edit-qty" min="1" required>
            </label>
            <label>
                Total Price (PHP)
                <input type="number" name="total" id="sup-edit-total" min="0" step="0.01" required>
            </label>
            <label style="grid-column:1/-1">
                Notes
                <textarea name="notes" id="sup-edit-notes" rows="3" maxlength="500" style="width:100%;padding:.5rem;border:1px solid #dfe8e1;border-radius:6px;"></textarea>
            </label>
            <div class="dialog-actions" style="grid-column:1/-1">
                <button type="button" class="secondary-button" data-close-admin-modal>Cancel</button>
                <button type="submit" class="primary-action">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('[data-admin-modal="super-edit-order-modal"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('sup-edit-customer').value = btn.dataset.customer || '';
        document.getElementById('sup-edit-food').value     = btn.dataset.food    || '';
        document.getElementById('sup-edit-qty').value      = btn.dataset.qty     || 1;
        document.getElementById('sup-edit-total').value    = parseFloat(btn.dataset.total || 0).toFixed(2);
        document.getElementById('sup-edit-notes').value    = btn.dataset.notes   || '';
        document.getElementById('super-edit-order-form').action =
            '/admin/customer-orders/' + btn.dataset.orderId + '/update';
    });
});
</script>
