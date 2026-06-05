@extends('layouts.app')

@php
    $isSuperAdmin = session('auth_admin_scope') === 'super';
    $statuses = ['Pending', 'Preparing', 'Ready for Pickup', 'Completed', 'Cancelled'];
    $statusColors = [
        'Pending'         => 'warning',
        'Preparing'       => 'blue',
        'Ready for Pickup'=> 'amber',
        'Completed'       => 'green',
        'Cancelled'       => 'danger',
    ];
    $money = fn ($v) => 'PHP ' . number_format((float) $v, 2);
@endphp

@section('content')
<section class="admin-hero compact-admin-hero">
    <div>
        <p class="crumb">Customer Orders Management</p>
        <h2>Role-Based Orders Control</h2>
        <p>
            @if ($isSuperAdmin)
                Super Admin — viewing all branches. You can edit, update status, and delete any order.
            @else
                Branch Admin — viewing only <strong>{{ session('auth_name') }}</strong> orders.
            @endif
        </p>
    </div>
</section>

@if (session('success'))
    <div class="activity-item success" style="margin:0 2rem 1rem;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="activity-item danger" style="margin:0 2rem 1rem;">{{ session('error') }}</div>
@endif

<section class="panel admin-table-gap" style="margin:2rem;">
    <div class="panel-head">
        <div>
            <h2>Customer Orders</h2>
            <p>{{ $orders->total() }} total orders found.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="admin-filter-bar" style="flex-wrap:wrap;gap:.75rem;">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by ID, customer, food...">

        @if ($isSuperAdmin)
            <select name="branch_id">
                <option value="">All Branches</option>
                @foreach ($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        @endif

        <select name="status">
            <option value="">All Statuses</option>
            @foreach ($statuses as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>

        <button type="submit" class="secondary-action">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="secondary-button">Reset</a>
    </form>

    <div class="table-wrap">
        <table class="history-table admin-data-table">
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
                @forelse ($orders as $order)
                    @php
                        $statusKey = $order->status ?? 'Pending';
                        $color = $statusColors[$statusKey] ?? 'muted';
                        $canEdit = $isSuperAdmin || (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') === (int) $order->branch_id);
                    @endphp
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->customer_name ?? '—' }}</td>
                        <td>{{ $order->branch->name ?? '—' }}</td>
                        <td>{{ $order->food_item ?? '—' }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $money($order->total) }}</td>
                        <td>{{ $order->order_date ? $order->order_date->format('M d, Y h:i A') : ($order->created_at ? $order->created_at->format('M d, Y h:i A') : '—') }}</td>
                        <td>
                            @if ($canEdit)
                                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="inline-status-form">
                                    @csrf @method('PATCH')
                                    <select name="status" class="status-select {{ strtolower(str_replace(' ', '-', $statusKey)) }}" onchange="this.form.submit()">
                                        @foreach ($statuses as $s)
                                            <option value="{{ $s }}" {{ $statusKey === $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span class="status-pill {{ strtolower(str_replace(' ', '-', $statusKey)) }}">{{ $statusKey }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                @if ($canEdit)
                                    <button type="button" class="secondary-button"
                                        data-admin-modal="edit-order-modal"
                                        data-order-id="{{ $order->id }}"
                                        data-customer="{{ $order->customer_name }}"
                                        data-food="{{ $order->food_item }}"
                                        data-qty="{{ $order->quantity }}"
                                        data-total="{{ $order->total }}"
                                        data-notes="{{ $order->notes }}"
                                        data-action="{{ route('admin.orders.update', $order->id) }}">
                                        Edit
                                    </button>
                                @endif
                                @if ($isSuperAdmin)
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Delete this order?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="danger">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;padding:2rem;color:#8a9a8e;">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:1rem 0;">
        {{ $orders->links() }}
    </div>
</section>

{{-- Edit Order Modal --}}
<div class="admin-modal" data-modal-id="edit-order-modal" aria-hidden="true">
    <div class="admin-modal-card">
        <header>
            <div>
                <p class="crumb">Edit Order</p>
                <h2>Update Order Details</h2>
            </div>
            <button type="button" class="secondary-button icon-button" data-close-admin-modal>✕</button>
        </header>

        <form id="edit-order-form" method="POST" action="" class="admin-form-grid">
            @csrf @method('PATCH')
            <input type="hidden" name="_method" value="PATCH">

            <label style="grid-column:1/-1">
                Customer Name
                <input type="text" name="customer_name" id="edit-customer-name" required maxlength="120">
            </label>

            <label style="grid-column:1/-1">
                Food Item
                <input type="text" name="food_item" id="edit-food-item" required maxlength="120">
            </label>

            <label>
                Quantity
                <input type="number" name="quantity" id="edit-quantity" min="1" required>
            </label>

            <label>
                Total Price (PHP)
                <input type="number" name="total" id="edit-total" min="0" step="0.01" required>
            </label>

            <label style="grid-column:1/-1">
                Notes
                <textarea name="notes" id="edit-notes" rows="3" maxlength="500" style="width:100%;padding:.5rem;border:1px solid #dfe8e1;border-radius:6px;"></textarea>
            </label>

            <div class="dialog-actions" style="grid-column:1/-1">
                <button type="button" class="secondary-button" data-close-admin-modal>Cancel</button>
                <button type="submit" class="primary-action">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('[data-admin-modal="edit-order-modal"]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-customer-name').value = btn.dataset.customer || '';
        document.getElementById('edit-food-item').value      = btn.dataset.food    || '';
        document.getElementById('edit-quantity').value       = btn.dataset.qty     || 1;
        document.getElementById('edit-total').value          = btn.dataset.total   || 0;
        document.getElementById('edit-notes').value          = btn.dataset.notes   || '';
        document.getElementById('edit-order-form').action    = btn.dataset.action;
    });
});

// Auto-compute total when qty changes (optional if unit price is known)
document.getElementById('edit-quantity').addEventListener('input', function () {
    // If you store unit price, you can compute here. For now it stays manual.
});
</script>
@endsection
