@php
    $cards = [
        ['label' => 'Total Orders', 'value' => $adminStats['total_orders'] ?? 0, 'icon' => 'O', 'desc' => 'all session orders', 'badge' => '+12%', 'tone' => 'blue'],
        ['label' => 'Total Revenue', 'value' => $adminStats['total_revenue'] ?? 0, 'prefix' => 'PHP', 'icon' => 'R', 'desc' => 'gross fake sales', 'badge' => '+18%', 'tone' => 'green'],
        ['label' => 'Total Foods', 'value' => $adminStats['total_foods'] ?? 0, 'icon' => 'F', 'desc' => 'menu items listed', 'badge' => 'Live', 'tone' => 'amber'],
        ['label' => 'Total Users', 'value' => $adminStats['total_users'] ?? 0, 'icon' => 'U', 'desc' => 'sample accounts', 'badge' => 'Stable', 'tone' => 'slate'],
        ['label' => 'Active Branches', 'value' => $adminStats['active_branches'] ?? 0, 'icon' => 'B', 'desc' => 'accepting orders', 'badge' => 'Online', 'tone' => 'green'],
        ['label' => 'Pending Orders', 'value' => $adminStats['pending_orders'] ?? 0, 'icon' => 'P', 'desc' => 'need admin action', 'badge' => 'Now', 'tone' => 'red'],
    ];
@endphp

<section class="admin-stat-grid">
    @foreach ($cards as $card)
        <article class="admin-overview-card {{ $card['tone'] }}">
            <div class="admin-card-icon">{{ $card['icon'] }}</div>
            <div>
                <span>{{ $card['label'] }}</span>
                <strong>
                    @if (! empty($card['prefix'])){{ $card['prefix'] }}@endif<span data-counter="{{ (float) $card['value'] }}">0</span>
                </strong>
                <p>{{ $card['desc'] }}</p>
            </div>
            <em>{{ $card['badge'] }}</em>
        </article>
    @endforeach
</section>
