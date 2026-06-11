<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'HostelEats' }} - Hostel Ordering System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet">
    @if (session('auth_role') === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $isAdmin = session('auth_role') === 'admin';
        $adminScope = session('auth_admin_scope', 'super');
        $adminBranchId = (int) session('auth_branch_id');
        $cartCount = collect($cartItems ?? [])->sum('qty');

        // Dynamic initials — works for both session-based and DB-based auth
        $authName = session('auth_name') ?? ($profile['name'] ?? (Auth::check() ? Auth::user()->name : 'Guest User'));
        $nameParts = array_filter(explode(' ', trim($authName)));
        $userInitials = strtoupper(substr($nameParts[0] ?? 'G', 0, 1));
        if (count($nameParts) > 1) {
            $userInitials .= strtoupper(substr(end($nameParts), 0, 1));
        }
        $adminInitials = $isAdmin ? $userInitials : 'AD';
        $displayBlock = session('auth_hostel_block')
            ? session('auth_hostel_block') . (session('auth_room_number') ? ' - ' . session('auth_room_number') : '')
            : ($profile['hostel_block'] ?? '');
        $adminSearchIndex = $isAdmin
            ? [
                'Foods' => collect($foods ?? [])->map(fn ($item) => ['label' => $item['name'] ?? 'Food', 'meta' => ($item['branch'] ?? '').' · '.($item['category'] ?? ''), 'route' => route('admin.foods')])->values(),
                'Orders' => collect($adminOrders ?? [])->map(fn ($item) => ['label' => $item['order_number'] ?? $item['id'] ?? 'Order', 'meta' => data_get($item, 'customer.full_name', 'Hostel Guest').' · '.($item['delivery_status'] ?? $item['status'] ?? ''), 'route' => route('admin.orders')])->values(),
                'Users' => collect($adminUsers ?? [])->map(fn ($item) => ['label' => $item['name'] ?? 'User', 'meta' => ($item['email'] ?? '').' · '.($item['hostel_block'] ?? ''), 'route' => route('admin.users')])->values(),
                'Branches' => collect($branches ?? [])->map(fn ($item) => ['label' => $item['name'] ?? 'Branch', 'meta' => ($item['description'] ?? '').' · '.($item['status_label'] ?? ''), 'route' => route('admin.branches')])->values(),
            ]
            : [];
        $navGroups = $isAdmin
            ? (
                $adminScope === 'branch'
                ? [
                    'Main' => [
                        ['label' => 'Dashboard', 'route' => 'admin.store.dashboard', 'params' => ['branch' => $adminBranchId], 'icon' => 'D'],
                        ['label' => 'Orders',    'route' => 'admin.store.dashboard', 'params' => ['branch' => $adminBranchId], 'icon' => 'O', 'hash' => 'store-orders-panel'],
                        ['label' => 'Customers', 'route' => 'admin.store.customers', 'params' => ['branchId' => $adminBranchId], 'icon' => 'C'],
                        ['label' => 'Analytics', 'route' => 'admin.store.dashboard', 'params' => ['branch' => $adminBranchId], 'icon' => 'A', 'hash' => 'sales-reports'],
                        ['label' => 'Settings',  'route' => 'admin.settings', 'icon' => 'S'],
                        ['label' => 'Logout',    'route' => 'hostel.logout',  'icon' => 'L', 'logout' => true],
                    ],
                ]
                : [
                    'Admin' => [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard',   'icon' => 'D'],
                        ['label' => 'Foods',     'route' => 'admin.foods',        'icon' => 'F'],
                        ['label' => 'Orders',    'route' => 'admin.orders',       'icon' => 'O'],
                        ['label' => 'Customers', 'route' => 'admin.customers',    'icon' => 'C'],
                        ['label' => 'Branches',  'route' => 'admin.branches',     'icon' => 'B'],
                        ['label' => 'Users',     'route' => 'admin.users',        'icon' => 'U'],
                    ],
                    'Store Dashboards' => collect($branches ?? [])->map(fn ($branch) => [
                        'label'  => $branch['name'] ?? 'Store',
                        'route'  => 'admin.store.dashboard',
                        'params' => ['branch' => $branch['id'] ?? $branch['slug'] ?? 1],
                        'icon'   => strtoupper(substr($branch['name'] ?? 'S', 0, 1)),
                    ])->values()->all(),
                    'Insights' => [
                        ['label' => 'Analytics', 'route' => 'admin.analytics', 'icon' => 'A'],
                        ['label' => 'Reports',   'route' => 'admin.reports',   'icon' => 'R'],
                    ],
                    'System' => [
                        ['label' => 'Settings', 'route' => 'admin.settings', 'icon' => 'S'],
                        ['label' => 'Logout',   'route' => 'hostel.logout',  'icon' => 'L', 'logout' => true],
                    ],
                ]
            )
            : [
                'Main' => [
                    ['label' => 'Dashboard',     'route' => 'dashboard',       'icon' => 'D'],
                    ['label' => 'All Menu',       'route' => 'menu.index',      'icon' => 'M'],
                    ['label' => 'Branches',       'route' => 'branches.index',  'icon' => 'B'],
                    ['label' => 'Categories',     'route' => 'categories.index','icon' => 'C'],
                ],
                'Orders' => [
                    ['label' => 'My Orders',      'route' => 'orders.current',  'icon' => 'O', 'badge' => $cartCount],
                    ['label' => 'Order History',  'route' => 'orders.history',  'icon' => 'H'],
                ],
                'Account' => [
                    ['label' => 'Profile',  'route' => 'profile.show',   'icon' => 'P'],
                    ['label' => 'Settings', 'route' => 'settings.index', 'icon' => 'S'],
                    ['label' => 'Logout',   'route' => 'hostel.logout',  'icon' => 'L', 'logout' => true],
                ],
            ];

        if ($isAdmin && $adminScope === 'branch') {
            $branch = collect($branches ?? [])->firstWhere('id', $adminBranchId);
            $branchName = $branch['name'] ?? 'Store';
            $adminSearchIndex = [
                'Foods' => collect($foods ?? [])->where('branch_id', $adminBranchId)->map(fn ($item) => ['label' => $item['name'] ?? 'Food', 'meta' => $branchName.' branch inventory', 'route' => route('admin.store.dashboard', $adminBranchId)])->values(),
                'Orders' => collect($adminOrders ?? [])->where('branch', $branchName)->map(fn ($item) => ['label' => $item['order_number'] ?? $item['id'] ?? 'Order', 'meta' => data_get($item, 'customer.full_name', 'Hostel Guest').' branch order', 'route' => route('admin.store.dashboard', $adminBranchId)])->values(),
                'Branches' => collect([$branch])->filter()->map(fn ($item) => ['label' => $item['name'] ?? 'Branch', 'meta' => 'Assigned branch only', 'route' => route('admin.store.dashboard', $adminBranchId)])->values(),
            ];
        }
    @endphp

    <div class="app-shell" data-hostel-eats>
        <aside class="sidebar" data-sidebar>
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">HE</span>
                <span>
                    <strong>HostelEats</strong>
                    <small>Hostel Ordering System</small>
                </span>
            </a>

            <button class="sidebar-toggle" type="button" data-sidebar-toggle>
                <span>Menu</span>
                <strong>|||</strong>
            </button>

            <nav class="nav" data-sidebar-nav>
                @foreach ($navGroups as $group => $links)
                    @if ($isAdmin && $adminScope === 'branch' && ! in_array($group, ['Main'], true))
                        @continue
                    @endif
                    <span class="nav-label">{{ $group }}</span>
                    @foreach ($links as $link)
                        @if ($isAdmin && $adminScope === 'branch' && ($link['route'] ?? '') === 'admin.store.dashboard' && (int) data_get($link, 'params.branch') !== $adminBranchId)
                            @continue
                        @endif
                        <a
                            class="nav-link {{ request()->routeIs($link['route']) || ($link['route'] === 'dashboard' && request()->routeIs('dashboard.alias')) ? 'active' : '' }}"
                            href="{{ route($link['route'], $link['params'] ?? []) }}{{ isset($link['hash']) ? '#'.$link['hash'] : '' }}"
                            @if (! empty($link['logout'])) data-logout-link @endif
                        >
                            <span class="icon">{{ $link['icon'] }}</span>
                            <span>{{ $link['label'] }}</span>
                            @if (! empty($link['badge']))
                                <em class="nav-badge" data-cart-badge>{{ $link['badge'] }}</em>
                            @endif
                        </a>
                    @endforeach
                @endforeach
            </nav>

            <section class="student-card">
                <span class="avatar">{{ $isAdmin ? $adminInitials : $userInitials }}</span>
                <span>
                    <strong>{{ $authName }}</strong>
                    <small>{{ $isAdmin ? ($adminScope === 'super' ? 'Super Admin Session' : 'Branch Admin Session') : ($displayBlock ?: 'Hostel Student') }}</small>
                </span>
            </section>
        </aside>

        <div class="workspace">
            <header class="topbar">
                <div>
                    <p class="crumb">Hostel Ordering System</p>
                    <h1>{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <form class="search {{ $isAdmin ? 'admin-global-search' : '' }}" role="search">
                    <input type="search" placeholder="{{ $isAdmin ? 'Search foods, orders, users, branches...' : 'Search menu, branch, category...' }}" data-global-search autocomplete="off">
                    <span class="search-icon">S</span>
                    @if ($isAdmin)
                        <div class="admin-global-results" data-admin-global-results></div>
                    @endif
                </form>
                @if ($isAdmin)
                    <div class="admin-top-actions">
                        <button type="button" class="notification-button" data-admin-modal="notifications">
                            N
                            <em>{{ count($notifications ?? []) }}</em>
                        </button>
                        <div class="admin-profile-menu">
                            <button type="button" class="cart-pill" data-admin-profile-toggle>
                                <span>{{ $adminInitials }}</span>
                                <strong>{{ session('auth_name') ?? 'Admin' }}</strong>
                            </button>
                            <div class="admin-profile-dropdown" data-admin-profile-menu>
                                <a href="{{ route('admin.settings') }}">My Profile</a>
                                <a href="{{ route('admin.settings') }}">Settings</a>
                                <a href="{{ route('hostel.logout') }}" data-logout-link>Logout</a>
                            </div>
                        </div>
                    </div>
                @else
                    <a class="cart-pill" href="{{ route('orders.current') }}" data-open-cart-sidebar>
                        <span>C</span>
                        <strong>Cart</strong>
                        <em data-cart-badge>{{ $cartCount }}</em>
                    </a>
                @endif
            </header>

            <main class="content">
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>

            @if (! $isAdmin)
            <footer class="he-footer">
                <div class="he-footer-inner">
                    <div class="he-footer-grid">
                        <div class="he-footer-brand">
                            <div class="brand-mark">HE</div>
                            <strong>HostelEats</strong>
                            <p>Your campus food ordering system. Order from your favourite branches — Jollibee, McDonald's, Mang Inasal, KFC, and Starbucks — delivered right to your hostel.</p>
                            <div style="display:flex;gap:10px;margin-top:4px;">
                                <a href="#" style="display:grid;width:34px;height:34px;place-items:center;border-radius:8px;background:#374151;color:#d1d5db;text-decoration:none;font-size:.85rem;font-weight:900;transition:background 160ms;" onmouseover="this.style.background='#f97316'" onmouseout="this.style.background='#374151'">f</a>
                                <a href="#" style="display:grid;width:34px;height:34px;place-items:center;border-radius:8px;background:#374151;color:#d1d5db;text-decoration:none;font-size:.85rem;font-weight:900;transition:background 160ms;" onmouseover="this.style.background='#f97316'" onmouseout="this.style.background='#374151'">in</a>
                                <a href="#" style="display:grid;width:34px;height:34px;place-items:center;border-radius:8px;background:#374151;color:#d1d5db;text-decoration:none;font-size:.85rem;font-weight:900;transition:background 160ms;" onmouseover="this.style.background='#f97316'" onmouseout="this.style.background='#374151'">tw</a>
                            </div>
                        </div>

                        <div class="he-footer-col">
                            <h4>Quick Links</h4>
                            <ul>
                                <li><a href="{{ route('dashboard') }}">🏠 Home</a></li>
                                <li><a href="{{ route('menu.index') }}">🍔 All Menu</a></li>
                                <li><a href="{{ route('branches.index') }}">🏪 Branches</a></li>
                                <li><a href="{{ route('categories.index') }}">📋 Categories</a></li>
                                <li><a href="{{ route('orders.current') }}">📦 My Orders</a></li>
                            </ul>
                        </div>

                        <div class="he-footer-col">
                            <h4>Company</h4>
                            <ul>
                                <li><a href="#about">🏢 About Us</a></li>
                                <li><a href="#privacy">🔒 Privacy Policy</a></li>
                                <li><a href="#terms">📄 Terms of Service</a></li>
                                <li><a href="#faq">❓ FAQ</a></li>
                                <li><a href="#contact">📞 Contact Us</a></li>
                            </ul>
                        </div>

                        <div class="he-footer-col he-footer-contact" id="contact">
                            <h4>Contact Us</h4>
                            <p><span>📍</span> Hostel Campus, University Avenue, Cebu City 6000</p>
                            <p><span>📧</span> support@hosteleats.com</p>
                            <p><span>📞</span> +63 912 345 6789</p>
                            <p><span>⏰</span> Mon–Sun, 7:00 AM – 10:00 PM</p>
                        </div>
                    </div>

                    <div class="he-footer-bottom">
                        <p>© {{ date('Y') }} HostelEats. All rights reserved.</p>
                        <div class="he-footer-bottom-links">
                            <a href="#about">About Us</a>
                            <a href="#privacy">Privacy Policy</a>
                            <a href="#terms">Terms</a>
                            <a href="#contact">Contact</a>
                        </div>
                    </div>
                </div>
            </footer>
            @endif
        </div>
    </div>

    <div class="logout-modal" data-logout-modal aria-hidden="true">
        <div class="logout-dialog" role="dialog" aria-modal="true" aria-labelledby="logout-title">
            <h2 id="logout-title">Logout confirmation</h2>
            <p>Are you sure you want to logout from HostelEats?</p>
            <div class="dialog-actions">
                <button type="button" class="secondary-button" data-logout-cancel>Cancel</button>
                <a class="danger-button" href="{{ route('hostel.logout') }}" data-logout-confirm>Logout</a>
            </div>
        </div>
    </div>
    @if ($isAdmin)
        <div class="admin-modal" data-modal-id="notifications" aria-hidden="true">
            <div class="admin-modal-card">
                <header>
                    <div>
                        <p class="crumb">Notifications</p>
                        <h2>Recent alerts</h2>
                    </div>
                    <button type="button" class="secondary-button icon-button" data-close-admin-modal>X</button>
                </header>
                <div class="activity-list">
                    @foreach (($notifications ?? []) as $notice)
                        <div class="activity-item {{ $notice['tone'] ?? 'muted' }}">
                            <strong>{{ $notice['type'] }}</strong>
                            <span>{{ $notice['message'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    @if (! $isAdmin)
        @include('pages.partials.cart-sidebar')
    @endif
    @if ($isAdmin)
        <script>
            window.hostelAdminSearchIndex = @json($adminSearchIndex);
        </script>
    @endif
    @stack('scripts')
</body>
</html>
