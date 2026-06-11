@extends('layouts.app')

@php
    $money = fn ($v) => 'PHP ' . number_format((float) $v, 2);
    $isSuperAdmin = session('auth_admin_scope') === 'super';
@endphp

@section('content')

{{-- Hero --}}
<section class="admin-hero compact-admin-hero">
    <div>
        <p class="crumb">{{ $branchName }} — Branch Customers</p>
        <h2>Customer Management</h2>
        <p>
            @if ($isSuperAdmin)
                Viewing customers who ordered from <strong>{{ $branchName }}</strong>.
            @else
                Showing only customers from your assigned branch: <strong>{{ $branchName }}</strong>.
            @endif
        </p>
    </div>
    @if ($isSuperAdmin)
        <div class="hero-actions">
            <a class="secondary-action" href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
        </div>
    @endif
</section>

{{-- Stat Cards --}}
<section class="admin-stat-grid store-stat-grid" style="margin-bottom:1.5rem;">
    @foreach ([
        ['label' => 'Total Customers',        'value' => $totalCustomers,     'icon' => 'C', 'tone' => 'blue',  'desc' => 'ordered from this branch'],
        ['label' => 'Active Customers',        'value' => $activeCustomers,    'icon' => 'A', 'tone' => 'green', 'desc' => 'with non-cancelled orders'],
        ['label' => 'New This Month',          'value' => $newThisMonth,       'icon' => 'N', 'tone' => 'amber', 'desc' => 'registered this month'],
        ['label' => 'Returning Customers',     'value' => $returningCustomers, 'icon' => 'R', 'tone' => 'slate', 'desc' => 'placed more than 1 order'],
    ] as $card)
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

{{-- Recent Customers Widget --}}
<section class="panel admin-table-gap" style="margin-bottom:1.5rem;">
    <div class="panel-head">
        <div>
            <h2>Recent Customers</h2>
            <p>Latest 10 customers who ordered from {{ $branchName }}.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table class="history-table admin-data-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Order ID</th>
                    <th>Food Ordered</th>
                    <th>Total</th>
                    <th>Date Ordered</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentCustomers as $order)
                    @php $statusClass = strtolower(str_replace(' ', '-', $order->status ?? 'pending')); @endphp
                    <tr>
                        <td><strong>{{ $order->customer_name ?? ($order->user->name ?? '—') }}</strong></td>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->food_item ?? '—' }}</td>
                        <td>{{ $money($order->total) }}</td>
                        <td style="font-size:.8rem;color:#6b7280;">
                            {{ ($order->order_date ?? $order->created_at)?->format('M d, Y h:i A') ?? '—' }}
                        </td>
                        <td><span class="status-pill {{ $statusClass }}">{{ $order->status ?? 'Pending' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af;">No orders yet for this branch.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Full Customer Table --}}
<section class="panel admin-table-gap">
    <div class="panel-head">
        <div>
            <h2>Branch Customers</h2>
            <p>All {{ $totalCustomers }} customers who have placed at least one order at {{ $branchName }}.</p>
        </div>
    </div>

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search name, email..." data-admin-search="branch-customers-table">
        <select data-admin-filter="branch-customers-table" data-filter-key="type">
            <option value="">All customers</option>
            <option value="returning">Returning</option>
            <option value="new">New</option>
        </select>
    </div>

    <div class="table-wrap">
        <table class="history-table admin-data-table" id="branch-customers-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total Orders</th>
                    <th>Total Spent</th>
                    <th>Last Order</th>
                    <th>Member Since</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    @php
                        $nameParts   = array_filter(explode(' ', trim($customer->name)));
                        $initials    = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
                        if (count($nameParts) > 1) $initials .= strtoupper(substr(end($nameParts), 0, 1));
                        $lastOrder   = $customer->orders->first();
                        $isReturning = $customer->branch_order_count > 1;
                        $favFood     = $customer->orders->groupBy('food_item')->sortByDesc(fn($g) => $g->count())->keys()->first() ?? '—';
                        $orderHistory = $customer->orders->map(fn($o) => [
                            'id'     => $o->id,
                            'food'   => $o->food_item,
                            'total'  => $o->total,
                            'status' => $o->status,
                            'date'   => optional($o->order_date ?? $o->created_at)->format('M d, Y'),
                        ])->values()->toJson();
                    @endphp
                    <tr
                        data-searchable="{{ strtolower($customer->name . ' ' . $customer->email) }}"
                        data-type="{{ $isReturning ? 'returning' : 'new' }}">
                        <td>
                            <div class="admin-media-cell">
                                <span class="avatar mini-avatar">{{ $initials }}</span>
                                <span>
                                    <strong>{{ $customer->name }}</strong>
                                    <small>{{ $customer->hasVerifiedEmail() ? '✓ Verified' : '✗ Unverified' }}</small>
                                </span>
                            </div>
                        </td>
                        <td style="font-size:.85rem;">{{ $customer->email }}</td>
                        <td><strong>{{ $customer->branch_order_count }}</strong></td>
                        <td><strong>{{ $money($customer->branch_total_spent ?? 0) }}</strong></td>
                        <td style="font-size:.8rem;color:#6b7280;">
                            {{ $lastOrder?->order_date?->format('M d, Y') ?? $lastOrder?->created_at?->format('M d, Y') ?? '—' }}
                        </td>
                        <td style="font-size:.8rem;color:#6b7280;">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="status-pill {{ $isReturning ? 'delivered' : 'warning' }}">
                                {{ $isReturning ? 'Returning' : 'New' }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="secondary-button"
                                data-admin-modal="customer-profile-modal"
                                data-name="{{ $customer->name }}"
                                data-email="{{ $customer->email }}"
                                data-orders="{{ $customer->branch_order_count }}"
                                data-spent="{{ number_format($customer->branch_total_spent ?? 0, 2) }}"
                                data-fav="{{ $favFood }}"
                                data-last="{{ $lastOrder?->order_date?->format('M d, Y h:i A') ?? $lastOrder?->created_at?->format('M d, Y h:i A') ?? '—' }}"
                                data-since="{{ $customer->created_at->format('M d, Y') }}"
                                data-verified="{{ $customer->hasVerifiedEmail() ? 'Yes' : 'No' }}"
                                data-history="{{ htmlspecialchars($orderHistory, ENT_QUOTES, 'UTF-8') }}">
                                View Profile
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:3rem;color:#9ca3af;">
                            No customers have placed orders at {{ $branchName }} yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Customer Profile Modal --}}
<div class="admin-modal" data-modal-id="customer-profile-modal" aria-hidden="true">
    <div class="admin-modal-card" style="max-width:640px;">
        <header>
            <div>
                <p class="crumb">{{ $branchName }} — Customer Profile</p>
                <h2 id="modal-customer-name">Customer Name</h2>
            </div>
            <button type="button" class="secondary-button icon-button" data-close-admin-modal>✕</button>
        </header>

        {{-- Info Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem 1.5rem;margin-bottom:1.25rem;">
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Email</span><p id="modal-email" style="margin:.15rem 0 0;font-size:.9rem;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Verified</span><p id="modal-verified" style="margin:.15rem 0 0;font-size:.9rem;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Total Orders</span><p id="modal-orders" style="margin:.15rem 0 0;font-size:.9rem;font-weight:700;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Total Spent</span><p id="modal-spent" style="margin:.15rem 0 0;font-size:.9rem;font-weight:700;color:#16a34a;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Favourite Food</span><p id="modal-fav" style="margin:.15rem 0 0;font-size:.9rem;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Last Order</span><p id="modal-last" style="margin:.15rem 0 0;font-size:.9rem;"></p></div>
            <div><span style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Member Since</span><p id="modal-since" style="margin:.15rem 0 0;font-size:.9rem;"></p></div>
        </div>

        {{-- Order History --}}
        <div>
            <p style="font-size:.8rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.5rem;">
                Order History at {{ $branchName }}
            </p>
            <div class="table-wrap" style="max-height:220px;overflow-y:auto;">
                <table class="history-table" id="modal-order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Food</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="modal-order-body"></tbody>
                </table>
            </div>
        </div>

        <div class="dialog-actions" style="margin-top:1rem;">
            <button type="button" class="secondary-button" data-close-admin-modal>Close</button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-admin-modal="customer-profile-modal"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('modal-customer-name').textContent = btn.dataset.name;
        document.getElementById('modal-email').textContent         = btn.dataset.email;
        document.getElementById('modal-verified').textContent      = btn.dataset.verified;
        document.getElementById('modal-orders').textContent        = btn.dataset.orders + ' orders';
        document.getElementById('modal-spent').textContent         = 'PHP ' + btn.dataset.spent;
        document.getElementById('modal-fav').textContent           = btn.dataset.fav;
        document.getElementById('modal-last').textContent          = btn.dataset.last;
        document.getElementById('modal-since').textContent         = btn.dataset.since;

        var tbody   = document.getElementById('modal-order-body');
        tbody.innerHTML = '';
        var history = JSON.parse(btn.dataset.history || '[]');

        if (history.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:1rem;">No orders found.</td></tr>';
            return;
        }

        history.forEach(function (o) {
            var statusClass = (o.status || 'pending').toLowerCase().replace(/ /g, '-');
            tbody.innerHTML +=
                '<tr>' +
                '<td>#' + o.id + '</td>' +
                '<td>' + (o.food || '—') + '</td>' +
                '<td>PHP ' + parseFloat(o.total || 0).toFixed(2) + '</td>' +
                '<td><span class="status-pill ' + statusClass + '">' + (o.status || 'Pending') + '</span></td>' +
                '<td style="font-size:.8rem;color:#6b7280;">' + (o.date || '—') + '</td>' +
                '</tr>';
        });
    });
});
</script>

@endsection
