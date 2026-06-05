@extends('layouts.app')

@php
    $money = fn ($value) => 'PHP'.number_format((float) $value, 2);
    $isSuperAdmin = session('auth_admin_scope') === 'super';
    $storeCards = [
        ['label' => 'Total Sales Today', 'value' => $money($storeStats['sales_today']), 'icon' => 'S', 'desc' => 'Branch revenue today', 'tone' => 'green'],
        ['label' => 'Sales This Month', 'value' => $money($storeStats['sales_month']), 'icon' => 'M', 'desc' => 'Month-to-date sales', 'tone' => 'blue'],
        ['label' => 'Total Orders', 'value' => $storeStats['total_orders'], 'icon' => 'O', 'desc' => 'Orders for this store', 'tone' => 'amber'],
        ['label' => 'Inventory Items', 'value' => $storeStats['inventory_items'], 'icon' => 'I', 'desc' => 'Tracked menu stock', 'tone' => 'slate'],
        ['label' => 'Low Stock Items', 'value' => $storeStats['low_stock'], 'icon' => 'L', 'desc' => 'Need restocking soon', 'tone' => 'red'],
        ['label' => 'Pending Orders', 'value' => $storeStats['pending_orders'], 'icon' => 'P', 'desc' => 'Awaiting action', 'tone' => 'red'],
        ['label' => 'Completed Orders', 'value' => $storeStats['completed_orders'], 'icon' => 'C', 'desc' => 'Finished pickups', 'tone' => 'green'],
        ['label' => 'Cancelled Orders', 'value' => $storeStats['cancelled_orders'], 'icon' => 'X', 'desc' => 'Stopped orders', 'tone' => 'slate'],
    ];
@endphp

@section('content')
    <section class="admin-hero store-admin-hero">
        <div>
            <p class="crumb">Store-Specific Admin Dashboard</p>
            <h2>{{ $storeBranch['name'] }} control center</h2>
            <p>Only {{ $storeBranch['name'] }} sales, inventory, orders, and analytics are shown on this branch dashboard.</p>
        </div>
        @if ($isSuperAdmin)
            <div class="store-switcher" aria-label="Store dashboards">
                @foreach ($branches as $branch)
                    <a class="{{ ($branch['id'] ?? null) === ($storeBranch['id'] ?? null) ? 'active' : '' }}" href="{{ route('admin.store.dashboard', $branch['id']) }}">
                        {{ $branch['name'] }}
                    </a>
                @endforeach
            </div>
        @else
            <div class="branch-access-badge">
                <strong>Branch Admin Access</strong>
                <span>{{ $storeBranch['name'] }} data only</span>
            </div>
        @endif
    </section>

    <section class="admin-stat-grid store-stat-grid">
        @foreach ($storeCards as $card)
            <article class="admin-overview-card {{ $card['tone'] }}">
                <div class="admin-card-icon">{{ $card['icon'] }}</div>
                <div>
                    <span>{{ $card['label'] }}</span>
                    <strong>{{ $card['value'] }}</strong>
                    <p>{{ $card['desc'] }}</p>
                </div>
            </article>
        @endforeach
    </section>

    <section class="store-alert-grid">
        <article class="activity-item warning">
            <strong>Real-Time Order Notification</strong>
            <span>{{ $storeOrders->where('delivery_status', 'Pending')->count() }} pending {{ $storeBranch['name'] }} order needs review.</span>
        </article>
        @foreach ($storeInventory->whereIn('status', ['Low Stock', 'Out of Stock']) as $item)
            <article class="activity-item {{ $item['status'] === 'Out of Stock' ? 'danger' : 'warning' }}">
                <strong>{{ $item['status'] }} Alert</strong>
                <span>{{ $item['name'] }} has {{ $item['stock'] }} item{{ $item['stock'] === 1 ? '' : 's' }} remaining.</span>
            </article>
        @endforeach
    </section>

    <section class="panel admin-table-gap" id="inventory-panel">
        <div class="panel-head">
            <div>
                <h2>Inventory Management</h2>
                <p>Add inventory, update stock, delete items, search stock records, and monitor low or out-of-stock alerts.</p>
            </div>
            <div class="hero-actions">
                <a class="secondary-action" href="{{ route('admin.store.inventory.export', $storeBranch['id']) }}">Export Inventory</a>
                <button type="button" class="primary-action" data-admin-modal="store-inventory-modal">Add Inventory</button>
            </div>
        </div>

        <div class="admin-filter-bar store-filter-bar">
            <input type="search" placeholder="Search inventory..." data-admin-search="store-inventory-table">
            <select data-admin-filter="store-inventory-table" data-filter-key="status">
                <option value="">All stock statuses</option>
                <option value="in stock">In Stock</option>
                <option value="low stock">Low Stock</option>
                <option value="out of stock">Out of Stock</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="history-table admin-data-table" id="store-inventory-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Stock Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($storeInventory as $item)
                        <tr data-searchable="{{ strtolower($item['name'].' '.$item['status']) }}" data-status="{{ strtolower($item['status']) }}">
                            <td><strong>{{ $item['name'] }}</strong></td>
                            <td>{{ $item['stock'] }}</td>
                            <td><span class="status-pill {{ strtolower(str_replace(' ', '-', $item['status'])) }}">{{ $item['status'] }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" data-admin-modal="store-inventory-modal">Update Stock</button>
                                    <button type="button" class="danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel admin-table-gap" id="store-orders-panel">
        <div class="panel-head">
            <div>
                <h2>Customer Orders</h2>
                <p>All orders assigned to {{ $storeBranch['name'] }}. Branch admins can edit and update status only within this branch.</p>
            </div>
            <button type="button" class="secondary-action" data-sort-table="store-orders-table">Sort by date</button>
        </div>

        @if (session('success'))
            <div class="activity-item success" style="margin:0 0 1rem;">{{ session('success') }}</div>
        @endif

        <div class="admin-filter-bar store-filter-bar">
            <input type="search" placeholder="Search order, customer, food..." data-admin-search="store-orders-table">
            <select data-admin-filter="store-orders-table" data-filter-key="status">
                <option value="">All order statuses</option>
                @foreach (['Pending', 'Preparing', 'Ready for Pickup', 'Completed', 'Cancelled'] as $s)
                    <option value="{{ strtolower($s) }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-wrap">
            <table class="history-table admin-data-table" id="store-orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Food Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($storeOrders as $order)
                        @php
                            $statusVal = $order['delivery_status'] ?? 'Pending';
                            $statusClass = strtolower(str_replace(' ', '-', $statusVal));
                        @endphp
                        <tr
                            data-searchable="{{ strtolower(($order['id'] ?? '').' '.data_get($order, 'customer.full_name', '').' '.($order['food_item'] ?? '').' '.$statusVal) }}"
                            data-status="{{ strtolower($statusVal) }}"
                            data-date="{{ strtotime($order['created_at'] ?? 'now') }}">
                            <td><strong>{{ $order['id'] }}</strong></td>
                            <td>{{ data_get($order, 'customer.full_name', '—') }}</td>
                            <td>{{ $order['food_item'] ?? '—' }}</td>
                            <td>{{ $order['quantity'] ?? 1 }}</td>
                            <td>{{ $money($order['total'] ?? 0) }}</td>
                            <td>{{ isset($order['created_at']) ? \Illuminate\Support\Carbon::parse($order['created_at'])->format('M d, Y h:i A') : '—' }}</td>
                            <td>
                                <form method="POST"
                                    action="{{ route('admin.orders.status', $order['id']) }}"
                                    class="inline-status-form">
                                    @csrf @method('PATCH')
                                    <select
                                        name="delivery_status"
                                        class="status-select {{ $statusClass }}"
                                        onchange="this.form.submit()">
                                        @foreach (['Pending','Preparing','Ready for Pickup','Completed','Cancelled'] as $opt)
                                            <option value="{{ $opt }}" {{ $statusVal === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button type="button" class="secondary-button"
                                    data-admin-modal="store-edit-order-modal"
                                    data-order-id="{{ $order['id'] }}"
                                    data-customer="{{ data_get($order, 'customer.full_name', '') }}"
                                    data-food="{{ $order['food_item'] ?? '' }}"
                                    data-qty="{{ $order['quantity'] ?? 1 }}"
                                    data-total="{{ $order['total'] ?? 0 }}"
                                    data-notes="">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Edit Order Modal (Branch-scoped) --}}
    <div class="admin-modal" data-modal-id="store-edit-order-modal" aria-hidden="true">
        <div class="admin-modal-card">
            <header>
                <div>
                    <p class="crumb">{{ $storeBranch['name'] }} — Edit Order</p>
                    <h2>Update Customer Order</h2>
                </div>
                <button type="button" class="secondary-button icon-button" data-close-admin-modal>✕</button>
            </header>
            <p style="color:#e74c3c;font-size:.85rem;margin-bottom:1rem;">
                ⚠ You can only edit orders belonging to <strong>{{ $storeBranch['name'] }}</strong>.
            </p>
            <form id="store-edit-order-form" method="POST" action="" class="admin-form-grid">
                @csrf
                <input type="hidden" name="_method" value="PATCH">

                <label style="grid-column:1/-1">
                    Customer Name
                    <input type="text" name="customer_name" id="store-edit-customer" required maxlength="120">
                </label>

                <label style="grid-column:1/-1">
                    Food Item
                    <input type="text" name="food_item" id="store-edit-food" required maxlength="120">
                </label>

                <label>
                    Quantity
                    <input type="number" name="quantity" id="store-edit-qty" min="1" required>
                </label>

                <label>
                    Total Price (PHP) — auto
                    <input type="number" name="total" id="store-edit-total" min="0" step="0.01" required readonly style="background:#f5f7f5;">
                </label>

                <label style="grid-column:1/-1">
                    Notes
                    <textarea name="notes" id="store-edit-notes" rows="3" maxlength="500" style="width:100%;padding:.5rem;border:1px solid #dfe8e1;border-radius:6px;"></textarea>
                </label>

                <div class="dialog-actions" style="grid-column:1/-1">
                    <button type="button" class="secondary-button" data-close-admin-modal>Cancel</button>
                    <button type="submit" class="primary-action">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        var unitPrices = {};
        @foreach ($storeOrders as $o)
            unitPrices['{{ $o["id"] }}'] = {{ $o['quantity'] > 0 ? round(($o['total'] / $o['quantity']), 2) : 0 }};
        @endforeach

        document.querySelectorAll('[data-admin-modal="store-edit-order-modal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id    = btn.dataset.orderId;
                var qty   = parseInt(btn.dataset.qty) || 1;
                var total = parseFloat(btn.dataset.total) || 0;

                document.getElementById('store-edit-customer').value = btn.dataset.customer || '';
                document.getElementById('store-edit-food').value     = btn.dataset.food    || '';
                document.getElementById('store-edit-qty').value      = qty;
                document.getElementById('store-edit-total').value    = total.toFixed(2);
                document.getElementById('store-edit-notes').value    = btn.dataset.notes   || '';
                document.getElementById('store-edit-order-form').action =
                    '/admin/customer-orders/' + id + '/update';
            });
        });

        document.getElementById('store-edit-qty').addEventListener('input', function () {
            var form  = document.getElementById('store-edit-order-form');
            var parts = form.action.split('/');
            var id    = parts[parts.length - 2];
            var unit  = unitPrices[id] || 0;
            var qty   = parseInt(this.value) || 1;
            document.getElementById('store-edit-total').value = (unit * qty).toFixed(2);
        });
    })();
    </script>

    <section class="store-analytics-summary" id="sales-reports">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Sales Analytics</h2>
                    <p>Daily, weekly, and monthly revenue for {{ $storeBranch['name'] }}.</p>
                </div>
                <a class="secondary-action" href="{{ route('admin.store.sales.pdf', array_merge(['branch' => $storeBranch['id']], request()->only(['from', 'to']))) }}" target="_blank">Export PDF</a>
            </div>
            <form class="date-range-form" method="GET" action="{{ route('admin.store.dashboard', $storeBranch['id']) }}">
                <label>From <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->toDateString()) }}"></label>
                <label>To <input type="date" name="to" value="{{ request('to', now()->toDateString()) }}"></label>
                <button type="submit" class="secondary-button">Apply Date Range</button>
            </form>
            <div class="store-revenue-strip">
                <span>Daily Sales<strong>{{ $money($storeAnalytics['revenueToday']) }}</strong></span>
                <span>Total Revenue This Week<strong>{{ $money($storeAnalytics['revenueWeek']) }}</strong></span>
                <span>Total Revenue This Month<strong>{{ $money($storeAnalytics['revenueMonth']) }}</strong></span>
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Top 5 Best Selling Foods</h2>
                    <p>Ranked by total sold for this store.</p>
                </div>
            </div>
            <div class="table-wrap">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Food Item</th>
                            <th>Total Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($storeAnalytics['topFoods'] as $food => $sold)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $food }}</td>
                                <td>{{ $sold }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <section class="admin-analytics-grid store-chart-grid">
        <article class="panel chart-panel">
            <div class="panel-head"><div><h2>Sales Trend Chart</h2><p>Daily sales movement.</p></div></div>
            <canvas data-chart="salesTrend" data-values='@json($storeAnalytics["dailySales"])'></canvas>
        </article>
        <article class="panel chart-panel">
            <div class="panel-head"><div><h2>Revenue Chart</h2><p>Weekly revenue totals.</p></div></div>
            <canvas data-chart="revenue" data-values='@json($storeAnalytics["weeklySales"])'></canvas>
        </article>
        <article class="panel chart-panel">
            <div class="panel-head"><div><h2>Orders Chart</h2><p>Orders by status.</p></div></div>
            <canvas data-chart="ordersStatus" data-values='@json($storeAnalytics["orders"])'></canvas>
        </article>
        <article class="panel chart-panel">
            <div class="panel-head"><div><h2>Inventory Status Chart</h2><p>Stock health overview.</p></div></div>
            <canvas data-chart="inventoryStatus" data-values='@json($storeAnalytics["inventoryStatus"])'></canvas>
        </article>
        <article class="panel chart-panel store-wide-chart">
            <div class="panel-head"><div><h2>Top 5 Best Selling Foods Chart</h2><p>Food ranking by total sold.</p></div></div>
            <canvas data-chart="topFoods" data-values='@json($storeAnalytics["topFoods"])'></canvas>
        </article>
    </section>

    <div class="admin-modal" data-modal-id="store-inventory-modal" aria-hidden="true">
        <div class="admin-modal-card">
            <header>
                <div>
                    <p class="crumb">Inventory</p>
                    <h2>Add or Update Stock</h2>
                </div>
                <button type="button" class="secondary-button icon-button" data-close-admin-modal>X</button>
            </header>
            <form class="admin-form-grid">
                <input type="text" placeholder="Food item">
                <input type="number" placeholder="Stock quantity" min="0">
                <input type="text" placeholder="Status">
                <button type="button" class="primary-action">Save Inventory</button>
            </form>
        </div>
    </div>
@endsection
