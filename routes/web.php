<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BranchPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$sampleData = function (): array {
    $branches = [
        [
            'id' => 1,
            'name' => 'Jollibee',
            'description' => 'Filipino fast-food favorites',
            'status' => 'Open now',
            'accent' => 'red',
            'initials' => 'JB',
            'banner' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=1400&q=80',
            'logo' => '/images/branches/jollibee-logo.svg',
        ],
        [
            'id' => 2,
            'name' => "McDonald's",
            'description' => 'World-famous burgers and fries',
            'status' => 'Open now',
            'accent' => 'yellow',
            'initials' => 'MC',
            'banner' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=1400&q=80',
            'logo' => '/images/branches/mcdonalds-logo.svg',
        ],
        [
            'id' => 3,
            'name' => 'Mang Inasal',
            'description' => 'Grilled chicken and Pinoy classics',
            'status' => 'Open now',
            'accent' => 'green',
            'initials' => 'MI',
            'banner' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=1400&q=80',
            'logo' => '/images/branches/mang-inasal-logo.svg',
        ],
        [
            'id' => 4,
            'name' => 'KFC',
            'description' => "Finger lickin' good chicken meals",
            'status' => 'Open now',
            'accent' => 'crimson',
            'initials' => 'KF',
            'banner' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&w=1400&q=80',
            'logo' => '/images/branches/kfc-logo.svg',
        ],
        [
            'id' => 5,
            'name' => 'Starbucks',
            'description' => 'Coffee, drinks and pastries',
            'status' => 'Open now',
            'accent' => 'sage',
            'initials' => 'SB',
            'banner' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=80',
            'logo' => '/images/branches/starbucks-logo.svg',
        ],
    ];

    $slugify = fn (string $value): string => strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $value), '-'));
    $branches = array_map(function (array $branch) use ($slugify): array {
        $branch['slug'] = $slugify($branch['name']);
        $branch['branch_url'] = '/branches/'.$branch['id'];
        $branch['orders_today'] = match ($branch['name']) {
            'Jollibee' => 34,
            "McDonald's" => 28,
            'Mang Inasal' => 25,
            'KFC' => 19,
            default => 14,
        };
        $branch['status_label'] = in_array($branch['name'], ['KFC'], true) ? 'Inactive' : 'Active';

        return $branch;
    }, $branches);

    $foods = [
        ['name' => 'Chickenjoy', 'branch' => 'Jollibee', 'category' => 'Chicken', 'price' => 95, 'tag' => 'Crispy favorite', 'image' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Jolly Spaghetti', 'branch' => 'Jollibee', 'category' => 'Pasta', 'price' => 75, 'tag' => 'Sweet style', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Burger Steak', 'branch' => 'Jollibee', 'category' => 'Chicken', 'price' => 89, 'tag' => 'Rice meal', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Yum Burger', 'branch' => 'Jollibee', 'category' => 'Burgers', 'price' => 55, 'tag' => 'Classic snack', 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Palabok', 'branch' => 'Jollibee', 'category' => 'Pasta', 'price' => 105, 'tag' => 'Pinoy noodles', 'image' => 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?auto=format&fit=crop&w=900&q=80'],

        ['name' => 'Big Mac', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 180, 'tag' => 'Signature burger', 'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'McChicken', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 145, 'tag' => 'Chicken burger', 'image' => 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Fries', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 75, 'tag' => 'Golden side', 'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Cheeseburger', 'branch' => "McDonald's", 'category' => 'Burgers', 'price' => 120, 'tag' => 'Cheesy classic', 'image' => 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'McSpaghetti', 'branch' => "McDonald's", 'category' => 'Pasta', 'price' => 95, 'tag' => 'Pasta meal', 'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=900&q=80'],

        ['name' => 'Pecho Inasal', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 175, 'tag' => 'Grilled pecho', 'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'PM1 Chicken Inasal', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 145, 'tag' => 'Rice combo', 'image' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Halo-Halo', 'branch' => 'Mang Inasal', 'category' => 'Drinks', 'price' => 99, 'tag' => 'Cold dessert', 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Pork Sisig', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 135, 'tag' => 'Sizzling style', 'image' => 'https://images.unsplash.com/photo-1604909052743-94e838986d24?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Java Rice', 'branch' => 'Mang Inasal', 'category' => 'Chicken', 'price' => 45, 'tag' => 'Savory side', 'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=900&q=80'],

        ['name' => 'Original Recipe Chicken', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 145, 'tag' => 'Original recipe', 'image' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Zinger Burger', 'branch' => 'KFC', 'category' => 'Burgers', 'price' => 155, 'tag' => 'Spicy crunch', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Famous Bowl', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 135, 'tag' => 'Loaded bowl', 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Hotshots', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 99, 'tag' => 'Bite sized', 'image' => 'https://images.unsplash.com/photo-1527477396000-e27163b481c2?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Mashed Potato', 'branch' => 'KFC', 'category' => 'Chicken', 'price' => 65, 'tag' => 'Creamy side', 'image' => 'https://images.unsplash.com/photo-1633436375795-12b3b339712f?auto=format&fit=crop&w=900&q=80'],

        ['name' => 'Caramel Frappuccino', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 205, 'tag' => 'Blended coffee', 'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Iced Americano', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 165, 'tag' => 'Cool espresso', 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Cappuccino', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 175, 'tag' => 'Foamy classic', 'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Chocolate Cake', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 185, 'tag' => 'Rich slice', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=80'],
        ['name' => 'Blueberry Cheesecake', 'branch' => 'Starbucks', 'category' => 'Coffee', 'price' => 195, 'tag' => 'Creamy dessert', 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=900&q=80'],
    ];

    $photoImages = [
        'Chickenjoy' => 'https://images.pexels.com/photos/106343/pexels-photo-106343.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Jolly Spaghetti' => 'https://images.pexels.com/photos/1279330/pexels-photo-1279330.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Burger Steak' => 'https://images.pexels.com/photos/675951/pexels-photo-675951.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Yum Burger' => 'https://images.pexels.com/photos/1633578/pexels-photo-1633578.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Palabok' => 'https://images.pexels.com/photos/2347311/pexels-photo-2347311.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Big Mac' => 'https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg?auto=compress&cs=tinysrgb&w=900',
        'McChicken' => 'https://images.pexels.com/photos/1600711/pexels-photo-1600711.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Fries' => 'https://images.pexels.com/photos/1583884/pexels-photo-1583884.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Cheeseburger' => 'https://images.pexels.com/photos/2983101/pexels-photo-2983101.jpeg?auto=compress&cs=tinysrgb&w=900',
        'McSpaghetti' => 'https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Pecho Inasal' => 'https://images.pexels.com/photos/2338407/pexels-photo-2338407.jpeg?auto=compress&cs=tinysrgb&w=900',
        'PM1 Chicken Inasal' => 'https://images.pexels.com/photos/6210876/pexels-photo-6210876.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Halo-Halo' => 'https://images.pexels.com/photos/1352278/pexels-photo-1352278.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Pork Sisig' => 'https://images.pexels.com/photos/533325/pexels-photo-533325.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Java Rice' => 'https://images.pexels.com/photos/723198/pexels-photo-723198.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Original Recipe Chicken' => 'https://images.pexels.com/photos/60616/fried-chicken-chicken-fried-crunchy-60616.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Zinger Burger' => 'https://images.pexels.com/photos/2271107/pexels-photo-2271107.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Famous Bowl' => 'https://images.pexels.com/photos/1640774/pexels-photo-1640774.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Hotshots' => 'https://images.pexels.com/photos/5840088/pexels-photo-5840088.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Mashed Potato' => 'https://images.pexels.com/photos/4110251/pexels-photo-4110251.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Caramel Frappuccino' => 'https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Iced Americano' => 'https://images.pexels.com/photos/2615323/pexels-photo-2615323.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Cappuccino' => 'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Chocolate Cake' => 'https://images.pexels.com/photos/291528/pexels-photo-291528.jpeg?auto=compress&cs=tinysrgb&w=900',
        'Blueberry Cheesecake' => 'https://images.pexels.com/photos/1126359/pexels-photo-1126359.jpeg?auto=compress&cs=tinysrgb&w=900',
    ];

    $branchIds = collect($branches)->pluck('id', 'name');

    $foods = array_map(function (array $food) use ($slugify, $photoImages, $branchIds): array {
        $food['id'] = $slugify($food['branch'].' '.$food['name']);
        $food['branch_id'] = (int) $branchIds[$food['branch']];
        $food['branch_slug'] = $slugify($food['branch']);
        $food['branch_url'] = '/branches/'.$food['branch_id'];
        $food['photo'] = $photoImages[$food['name']] ?? $food['image'];
        $food['fallback_image'] = '/images/foods/'.$slugify($food['name']).'.svg';
        $food['available'] = ! in_array($food['name'], ['Halo-Halo', 'Mashed Potato'], true);
        $food['stock'] = match ($food['category']) {
            'Coffee' => 18,
            'Drinks' => 8,
            'Pasta' => 14,
            'Burgers' => 21,
            default => 26,
        };
        $food['sold'] = match ($food['name']) {
            'Chickenjoy' => 74,
            'Big Mac' => 58,
            'Pecho Inasal' => 51,
            'Caramel Frappuccino' => 47,
            default => 20 + strlen($food['name']) % 24,
        };

        return $food;
    }, $foods);

    $sessionOrders = collect(session('orders', []))->values();
    $sampleOrders = collect([
        [
            'id' => 'HE-20260528001',
            'order_number' => 'HE-20260528001',
            'foods' => 'Chickenjoy, Jolly Spaghetti',
            'items' => [
                ['name' => 'Chickenjoy', 'qty' => 2],
                ['name' => 'Jolly Spaghetti', 'qty' => 1],
            ],
            'total' => 265,
            'status' => 'pending',
            'delivery_status' => 'Pending',
            'branch' => 'Jollibee',
            'customer' => ['full_name' => 'Mika Reyes', 'hostel_block' => 'Block A', 'room_number' => '102'],
            'created_at' => now()->subMinutes(18)->toDateTimeString(),
        ],
        [
            'id' => 'HE-20260528002',
            'order_number' => 'HE-20260528002',
            'foods' => 'Big Mac, Fries',
            'total' => 255,
            'status' => 'preparing',
            'delivery_status' => 'Preparing',
            'branch' => "McDonald's",
            'customer' => ['full_name' => 'Andre Santos', 'hostel_block' => 'Block C', 'room_number' => '214'],
            'created_at' => now()->subMinutes(42)->toDateTimeString(),
        ],
        [
            'id' => 'HE-20260527004',
            'order_number' => 'HE-20260527004',
            'foods' => 'Pecho Inasal, Java Rice',
            'total' => 220,
            'status' => 'delivered',
            'delivery_status' => 'Delivered',
            'branch' => 'Mang Inasal',
            'customer' => ['full_name' => 'Lara Lim', 'hostel_block' => 'Block B', 'room_number' => '305'],
            'created_at' => now()->subDay()->toDateTimeString(),
        ],
        [
            'id' => 'HE-20260526009',
            'order_number' => 'HE-20260526009',
            'foods' => 'Zinger Burger',
            'total' => 155,
            'status' => 'cancelled',
            'delivery_status' => 'Cancelled',
            'branch' => 'KFC',
            'customer' => ['full_name' => 'Nico Tan', 'hostel_block' => 'Block D', 'room_number' => '117'],
            'created_at' => now()->subDays(2)->toDateTimeString(),
        ],
    ]);

    $adminOrders = $sessionOrders->map(function (array $order): array {
        $order['branch'] = $order['branch'] ?? data_get($order, 'items.0.branch', 'HostelEats');
        $order['customer'] = $order['customer'] ?? ['full_name' => 'Hostel Guest', 'hostel_block' => 'Block A', 'room_number' => 'N/A'];
        return $order;
    })->merge($sampleOrders)->sortByDesc('created_at')->values();

    $users = [
        ['name' => 'Mika Reyes', 'email' => 'mika@student.edu', 'hostel_block' => 'Block A - Room 102', 'status' => 'Active', 'avatar' => 'MR'],
        ['name' => 'Andre Santos', 'email' => 'andre@student.edu', 'hostel_block' => 'Block C - Room 214', 'status' => 'Active', 'avatar' => 'AS'],
        ['name' => 'Lara Lim', 'email' => 'lara@student.edu', 'hostel_block' => 'Block B - Room 305', 'status' => 'Active', 'avatar' => 'LL'],
        ['name' => 'Nico Tan', 'email' => 'nico@student.edu', 'hostel_block' => 'Block D - Room 117', 'status' => 'Disabled', 'avatar' => 'NT'],
        ['name' => 'Bea Cruz', 'email' => 'bea@student.edu', 'hostel_block' => 'Block A - Room 220', 'status' => 'Active', 'avatar' => 'BC'],
    ];

    $analytics = [
        'dailyOrders' => [18, 24, 21, 32, 29, 38, 43],
        'revenue' => [2400, 3150, 2885, 4210, 3975, 5120, 5740],
        'topFoods' => ['Chickenjoy' => 74, 'Big Mac' => 58, 'Pecho Inasal' => 51, 'Caramel Frappe' => 47],
        'topBranches' => ['Jollibee' => 34, "McDonald's" => 28, 'Mang Inasal' => 25, 'KFC' => 19],
    ];

    $activeOrdersForAdmin = $adminOrders->filter(fn (array $order) => ! in_array(strtolower($order['delivery_status'] ?? $order['status'] ?? ''), ['delivered', 'cancelled'], true))->values();
    $revenueTotal = $adminOrders->filter(fn (array $order) => strtolower($order['delivery_status'] ?? '') !== 'cancelled')->sum('total');

    return [
        'branches' => $branches,
        'foods' => $foods,
        'foodsByBranch' => collect($foods)->groupBy('branch'),
        'categories' => [
            ['name' => 'Burgers', 'count' => 6, 'description' => 'Stacked sandwiches, fries, and student-friendly combos.'],
            ['name' => 'Chicken', 'count' => 10, 'description' => 'Fried, grilled, spicy, saucy, and always filling.'],
            ['name' => 'Pasta', 'count' => 3, 'description' => 'Sweet spaghetti and warm comfort bowls.'],
            ['name' => 'Drinks', 'count' => 1, 'description' => 'Cold refreshers, sodas, tea, and blended drinks.'],
            ['name' => 'Coffee', 'count' => 5, 'description' => 'Campus fuel for early classes and late study nights.'],
        ],
        'cartItems' => collect(session('cart', []))->values()->all(),
        'activeOrders' => collect(session('orders', []))
            ->sortByDesc('created_at')
            ->values()
            ->all(),
        'history' => collect(session('orders', []))
            ->filter(fn (array $order) => in_array(strtolower($order['delivery_status'] ?? $order['status'] ?? ''), ['delivered', 'completed'], true))
            ->sortByDesc('created_at')
            ->values()
            ->all(),
        'adminOrders' => $adminOrders->all(),
        'adminUsers' => $users,
        'analytics' => $analytics,
        'notifications' => [
            ['type' => 'New order', 'message' => 'HE-20260528001 needs confirmation.', 'tone' => 'warning'],
            ['type' => 'Low stock', 'message' => 'Halo-Halo stock is below 10 servings.', 'tone' => 'danger'],
            ['type' => 'Branch', 'message' => 'KFC is currently marked inactive.', 'tone' => 'muted'],
        ],
        'adminStats' => [
            'total_orders' => $adminOrders->count(),
            'total_revenue' => $revenueTotal,
            'total_foods' => count($foods),
            'total_users' => count($users),
            'active_branches' => collect($branches)->where('status_label', 'Active')->count(),
            'pending_orders' => $activeOrdersForAdmin->count(),
        ],
        'profile' => [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@student.edu',
            'student_id' => 'STU-2026-1048',
            'hostel_block' => 'Block C - Room 214',
        ],
    ];
};

$render = function (string $view, string $title, array $extra = []) use ($sampleData) {
    $data = $sampleData();

    if (session('auth_role') === 'user') {
        $data['profile'] = [
            'name' => session('auth_name', $data['profile']['name']),
            'email' => session('auth_email', $data['profile']['email']),
            'student_id' => session('auth_student_id', $data['profile']['student_id']),
            'hostel_block' => session('auth_hostel_block', $data['profile']['hostel_block']),
        ];
    }

    return view($view, array_merge($data, ['title' => $title], $extra));
};

$userCredentials = ['email' => 'user@gmail.com', 'password' => '123456'];
$adminCredentials = ['email' => 'admin@gmail.com', 'password' => 'admin123'];
$adminAccounts = [
    'admin@gmail.com' => ['password' => 'admin123', 'name' => 'HostelEats Super Admin', 'scope' => 'super', 'branch_id' => null],
    'jollibee.admin@hosteleats.test' => ['password' => 'branch123', 'name' => 'Jollibee Admin', 'scope' => 'branch', 'branch_id' => 1],
    'mcdonalds.admin@hosteleats.test' => ['password' => 'branch123', 'name' => "McDonald's Admin", 'scope' => 'branch', 'branch_id' => 2],
    'manginasal.admin@hosteleats.test' => ['password' => 'branch123', 'name' => 'Mang Inasal Admin', 'scope' => 'branch', 'branch_id' => 3],
    'kfc.admin@hosteleats.test' => ['password' => 'branch123', 'name' => 'KFC Admin', 'scope' => 'branch', 'branch_id' => 4],
    'starbucks.admin@hosteleats.test' => ['password' => 'branch123', 'name' => 'Starbucks Admin', 'scope' => 'branch', 'branch_id' => 5],
];

$requireUser = function () {
    if (session('auth_role') !== 'user') {
        return redirect()->route('login')->with('error', 'Please login as user first.');
    }

    return null;
};

$requireAdmin = function () {
    if (session('auth_role') !== 'admin') {
        return redirect()->route('admin.login')->with('error', 'Please login as admin first.');
    }

    return null;
};

$branchAdminRedirect = function (string $message = 'Your branch admin account is not assigned to a store. Please login again or contact the system admin.') {
    $branchId = session('auth_branch_id');

    if ($branchId) {
        return redirect()->route('admin.store.dashboard', $branchId)->with('error', $message);
    }

    session()->forget(['auth_role', 'auth_name', 'auth_email', 'auth_admin_scope', 'auth_branch_id']);

    return redirect()->route('admin.login')->with('error', 'Your branch admin account is not assigned to a store. Please login again or contact the system admin.');
};

$requireSuperAdmin = function () use ($requireAdmin, $branchAdminRedirect) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }

    if (session('auth_admin_scope') !== 'super') {
        return $branchAdminRedirect('Branch admins can only view their own store dashboard.');
    }

    return null;
};

$renderUser = function (string $view, string $title, array $extra = []) use ($render, $requireUser) {
    if ($redirect = $requireUser()) {
        return $redirect;
    }

    return $render($view, $title, $extra);
};

$renderAdmin = function (string $view, string $title, array $extra = []) use ($render, $requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }

    return $render($view, $title, $extra);
};

$renderSuperAdmin = function (string $view, string $title, array $extra = []) use ($render, $requireSuperAdmin) {
    if ($redirect = $requireSuperAdmin()) {
        return $redirect;
    }

    return $render($view, $title, $extra);
};

$storeDashboardData = function (array $data, array $branch): array {
    $branchName = $branch['name'];
    $branchSeed = ((int) ($branch['id'] ?? 1)) + 2;
    $topFoodNames = [
        'Jollibee' => ['Chickenjoy', 'Jolly Spaghetti', 'Burger Steak', 'Yum Burger', 'Palabok'],
        "McDonald's" => ['Big Mac', 'McChicken', 'Fries', 'Cheeseburger', 'McSpaghetti'],
        'Mang Inasal' => ['Chicken Inasal', 'Pork BBQ', 'Sisig', 'Halo-Halo', 'Rice Meal'],
        'KFC' => ['Original Recipe Chicken', 'Zinger Burger', 'Famous Bowl', 'Hot Shots', 'Fries'],
        'Starbucks' => ['Caramel Macchiato', 'Caffe Latte', 'Frappuccino', 'Mocha', 'Americano'],
    ][$branchName] ?? collect($data['foods'])->where('branch', $branchName)->pluck('name')->take(5)->values()->all();

    $stockByFood = [
        'Chickenjoy' => 50,
        'Jolly Spaghetti' => 20,
        'Yum Burger' => 0,
        'Big Mac' => 45,
        'McChicken' => 18,
        'Fries' => 0,
        'Chicken Inasal' => 38,
        'Pork BBQ' => 12,
        'Halo-Halo' => 0,
        'Original Recipe Chicken' => 42,
        'Zinger Burger' => 16,
        'Hot Shots' => 0,
        'Caramel Macchiato' => 30,
        'Caffe Latte' => 14,
        'Americano' => 0,
    ];

    $branchFoods = collect($data['foods'])
        ->where('branch', $branchName)
        ->values();

    $inventory = collect($topFoodNames)->map(function (string $name, int $index) use ($branchFoods, $stockByFood): array {
        $food = $branchFoods->firstWhere('name', $name) ?? [
            'name' => $name,
            'price' => 80 + ($index * 15),
            'category' => 'Store Item',
            'tag' => 'Branch item',
        ];
        $stock = $stockByFood[$name] ?? max(0, 48 - ($index * 11));

        return array_merge($food, [
            'stock' => $stock,
            'status' => $stock <= 0 ? 'Out of Stock' : ($stock <= 20 ? 'Low Stock' : 'In Stock'),
        ]);
    })->values();

    $orderStatuses = ['Pending', 'Preparing', 'Ready for Pickup', 'Completed', 'Cancelled'];
    $storeOrders = collect($topFoodNames)->map(function (string $food, int $index) use ($branchName, $branchSeed, $orderStatuses): array {
        $quantity = ($index % 3) + 1;
        $price = (85 + ($branchSeed * 12) + ($index * 18)) * $quantity;

        return [
            'id' => 'HE-'.now()->format('Ymd').str_pad((string) ($branchSeed * 100 + $index + 1), 3, '0', STR_PAD_LEFT),
            'customer' => ['full_name' => ['Mika Reyes', 'Andre Santos', 'Lara Lim', 'Nico Tan', 'Bea Cruz'][$index] ?? 'Hostel Guest'],
            'food_item' => $food,
            'quantity' => $quantity,
            'total' => $price,
            'branch' => $branchName,
            'delivery_status' => $orderStatuses[$index] ?? 'Pending',
            'created_at' => now()->subMinutes(($index + 1) * 22)->toDateTimeString(),
        ];
    });

    $dailySales = collect([1, 2, 3, 4, 5, 6, 7])->map(fn (int $day): int => ($branchSeed * 280) + ($day * 190) + (($day % 2) * 140))->all();
    $weeklySales = collect([1, 2, 3, 4])->map(fn (int $week): int => ($branchSeed * 1300) + ($week * 850))->all();
    $monthlySales = collect([1, 2, 3, 4, 5, 6])->map(fn (int $month): int => ($branchSeed * 4200) + ($month * 1800))->all();
    $topFoods = collect($topFoodNames)->mapWithKeys(fn (string $food, int $index): array => [$food => max(70, 250 - ($index * 35) - ($branchSeed * 3))]);

    return [
        'storeBranch' => $branch,
        'storeInventory' => $inventory,
        'storeOrders' => $storeOrders->values(),
        'storeStats' => [
            'sales_today' => array_sum(array_slice($dailySales, -1)),
            'sales_month' => array_sum($monthlySales),
            'total_orders' => $storeOrders->count(),
            'inventory_items' => $inventory->count(),
            'low_stock' => $inventory->where('status', 'Low Stock')->count(),
            'pending_orders' => $storeOrders->where('delivery_status', 'Pending')->count(),
            'completed_orders' => $storeOrders->where('delivery_status', 'Completed')->count(),
            'cancelled_orders' => $storeOrders->where('delivery_status', 'Cancelled')->count(),
        ],
        'storeAnalytics' => [
            'dailySales' => $dailySales,
            'weeklySales' => $weeklySales,
            'monthlySales' => $monthlySales,
            'topFoods' => $topFoods->all(),
            'inventoryStatus' => [
                'In Stock' => $inventory->where('status', 'In Stock')->count(),
                'Low Stock' => $inventory->where('status', 'Low Stock')->count(),
                'Out of Stock' => $inventory->where('status', 'Out of Stock')->count(),
            ],
            'orders' => [
                'Pending' => $storeOrders->where('delivery_status', 'Pending')->count(),
                'Preparing' => $storeOrders->where('delivery_status', 'Preparing')->count(),
                'Ready' => $storeOrders->where('delivery_status', 'Ready for Pickup')->count(),
                'Completed' => $storeOrders->where('delivery_status', 'Completed')->count(),
                'Cancelled' => $storeOrders->where('delivery_status', 'Cancelled')->count(),
            ],
            'revenueToday' => array_sum(array_slice($dailySales, -1)),
            'revenueWeek' => array_sum($weeklySales),
            'revenueMonth' => array_sum($monthlySales),
        ],
    ];
};

Route::get('/images/foods/{slug}.svg', function (string $slug) use ($sampleData) {
    $food = collect($sampleData()['foods'])->first(fn (array $item) => str_ends_with($item['fallback_image'], '/'.$slug.'.svg'));

    if (! $food) {
        abort(404);
    }

    $palettes = [
        'Burgers' => ['#d7963a', '#613b22', '#f0b323'],
        'Chicken' => ['#f0b323', '#cf3e36', '#7a3f1d'],
        'Pasta' => ['#fff0b8', '#cf3e36', '#f0b323'],
        'Drinks' => ['#8ed6ff', '#315f9d', '#ffffff'],
        'Coffee' => ['#b9814a', '#4d2b1b', '#f6e6cf'],
    ];

    [$primary, $secondary, $accent] = $palettes[$food['category']] ?? ['#0f7c55', '#315f9d', '#f0b323'];
    $name = htmlspecialchars($food['name'], ENT_QUOTES, 'UTF-8');
    $branch = htmlspecialchars($food['branch'], ENT_QUOTES, 'UTF-8');
    $initial = htmlspecialchars(substr($food['name'], 0, 1), ENT_QUOTES, 'UTF-8');

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 620" role="img" aria-label="{$name}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$primary}"/>
      <stop offset="1" stop-color="{$secondary}"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="24" stdDeviation="24" flood-color="#000000" flood-opacity=".22"/>
    </filter>
  </defs>
  <rect width="900" height="620" rx="46" fill="url(#bg)"/>
  <circle cx="760" cy="90" r="150" fill="#ffffff" opacity=".18"/>
  <circle cx="128" cy="520" r="180" fill="#ffffff" opacity=".12"/>
  <ellipse cx="450" cy="410" rx="270" ry="82" fill="#ffffff" opacity=".92" filter="url(#shadow)"/>
  <ellipse cx="450" cy="386" rx="210" ry="58" fill="{$accent}" opacity=".92"/>
  <circle cx="365" cy="322" r="74" fill="#ffffff" opacity=".92"/>
  <circle cx="462" cy="300" r="96" fill="{$secondary}" opacity=".9"/>
  <circle cx="552" cy="332" r="68" fill="{$primary}" opacity=".96"/>
  <path d="M265 420c122 72 250 72 370 0" fill="none" stroke="#ffffff" stroke-width="32" stroke-linecap="round" opacity=".82"/>
  <rect x="54" y="48" width="132" height="132" rx="28" fill="#ffffff" opacity=".94"/>
  <text x="120" y="135" text-anchor="middle" font-family="Arial, sans-serif" font-size="74" font-weight="900" fill="{$secondary}">{$initial}</text>
  <text x="62" y="520" font-family="Arial, sans-serif" font-size="56" font-weight="900" fill="#ffffff">{$name}</text>
  <text x="64" y="572" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="#ffffff" opacity=".86">{$branch}</text>
</svg>
SVG;

    return response($svg, 200)->header('Content-Type', 'image/svg+xml');
})->where('slug', '[A-Za-z0-9\-]+');

Route::get('/login', fn () => $render('auth.login', 'User Login'))->name('login');
Route::post('/login', function (Request $request) use ($userCredentials) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $email    = strtolower(trim($credentials['email']));
    $password = (string) $credentials['password'];

    // ── 1. Check DB-registered users first ───────────────────────────────────
    try {
        $dbUser = \App\Models\User::where('email', $email)
            ->where('role', 'customer')
            ->first();
    } catch (\Throwable $e) {
        report($e);
        $dbUser = null;
    }

    if ($dbUser && \Illuminate\Support\Facades\Hash::check($password, $dbUser->password)) {
        // Unverified — generate fresh OTP and redirect to OTP page
        if (! $dbUser->hasVerifiedEmail()) {
            RegisterController::generateAndSendOtp($dbUser);
            session([
                'otp_user_id' => $dbUser->id,
                'otp_email'   => $dbUser->email,
                'otp_name'    => $dbUser->name,
            ]);
            return redirect()->route('otp.verify.form')
                ->with('success', 'A new verification code has been sent to ' . $dbUser->email);
        }

        $request->session()->regenerate();
        session([
            'auth_role'         => 'user',
            'auth_name'         => $dbUser->name,
            'auth_email'        => $dbUser->email,
            'auth_student_id'   => 'STU-' . str_pad($dbUser->id, 7, '0', STR_PAD_LEFT),
            'auth_hostel_block' => $dbUser->hostel_block ?? 'Block A',
            'auth_room_number'  => $dbUser->room_number  ?? '',
        ]);

        return redirect()->route('user.dashboard');
    }

    // ── 2. Fall back to legacy session-based demo user ────────────────────────
    $matchesDefaultUser = $email === strtolower($userCredentials['email'])
        && $credentials['password'] === $userCredentials['password'];

    if (! $matchesDefaultUser) {
        return back()->withInput($request->only('email'))->with('error', 'Wrong email or password.');
    }

    $request->session()->regenerate();
    session([
        'auth_role'         => 'user',
        'auth_name'         => 'Juan Dela Cruz',
        'auth_email'        => $credentials['email'],
        'auth_student_id'   => 'STU-2026-1048',
        'auth_hostel_block' => 'Block C - Room 214',
    ]);

    return redirect()->route('user.dashboard');
})->name('login.store');

Route::get('/signup', fn () => $render('auth.signup', 'Create Account'))->name('signup');
Route::post('/signup', [RegisterController::class, 'store'])->name('signup.store');

// ─── OTP Verification Routes ─────────────────────────────────────────────────
Route::get('/otp/verify',  [OtpController::class, 'showForm'])->name('otp.verify.form');
Route::post('/otp/verify', [OtpController::class, 'verify'])->middleware('throttle:10,1')->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->middleware('throttle:3,1')->name('otp.resend');
Route::get('/otp/success', [OtpController::class, 'success'])->name('otp.success');

Route::get('/admin/login', fn () => $render('auth.admin-login', 'Admin Login'))->name('admin.login');
Route::post('/admin/login', function (Request $request) use ($adminAccounts) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $email = strtolower(trim($credentials['email']));
    $account = $adminAccounts[$email] ?? null;

    if (! $account || $credentials['password'] !== $account['password']) {
        return back()->withInput($request->only('email'))->with('error', 'Wrong admin email or password.');
    }

    $request->session()->regenerate();
    session([
        'auth_role' => 'admin',
        'auth_name' => $account['name'],
        'auth_email' => $email,
        'auth_admin_scope' => $account['scope'],
        'auth_branch_id' => $account['branch_id'],
    ]);

    if ($account['scope'] === 'branch') {
        if (empty($account['branch_id'])) {
            $request->session()->forget(['auth_role', 'auth_name', 'auth_email', 'auth_admin_scope', 'auth_branch_id']);

            return back()->withInput($request->only('email'))->with('error', 'This branch admin account is not assigned to a store.');
        }

        return redirect()->route('admin.store.dashboard', $account['branch_id']);
    }

    return redirect()->route('admin.dashboard');
})->name('admin.login.store');

Route::get('/logout', function (Request $request) {
    $request->session()->forget(['auth_role', 'auth_name', 'auth_email', 'auth_admin_scope', 'auth_branch_id']);
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'You have been logged out.');
})->name('hostel.logout');

Route::get('/', function () use ($renderUser) {
    if (session('auth_role') === 'admin') {
        if (session('auth_admin_scope') === 'branch') {
            $branchId = session('auth_branch_id');

            if ($branchId) {
                return redirect()->route('admin.store.dashboard', $branchId);
            }

            session()->forget(['auth_role', 'auth_name', 'auth_email', 'auth_admin_scope', 'auth_branch_id']);

            return redirect()->route('admin.login')->with('error', 'Your branch admin account is not assigned to a store. Please login again or contact the system admin.');
        }

        return redirect()->route('admin.dashboard');
    }

    return $renderUser('pages.dashboard', 'Dashboard');
})->name('dashboard');
Route::get('/dashboard', fn () => $renderUser('pages.dashboard', 'Dashboard'))->name('dashboard.alias');
Route::get('/user/dashboard', fn () => $renderUser('pages.user-dashboard', 'User Dashboard'))->name('user.dashboard');
Route::get('/all-menu', fn () => $renderUser('pages.all-menu', 'All Menu'))->name('menu.index');
Route::get('/branches', fn () => $renderUser('pages.branches', 'Branches'))->name('branches.index');
Route::get('/branches/{branch}', [BranchPageController::class, 'show'])->name('branches.show');
Route::get('/categories', fn () => $renderUser('pages.categories', 'Categories'))->name('categories.index');
Route::get('/cart', function () {
    $items = collect(session('cart', []))->values();
    $subtotal = $items->sum(fn (array $item) => (float) $item['price'] * (int) $item['qty']);
    $delivery = $items->isEmpty() ? 0 : 15;

    return response()->json([
        'items' => $items,
        'count' => $items->sum('qty'),
        'subtotal' => $subtotal,
        'delivery' => $delivery,
        'total' => $subtotal + $delivery,
    ]);
})->name('cart.index');
Route::post('/cart', function (Request $request) {
    if (session('auth_role') !== 'user') {
        return response()->json(['message' => 'Please login as user first.'], 401);
    }

    $data = $request->validate([
        'id' => ['required', 'string'],
        'name' => ['required', 'string'],
        'branch' => ['required', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
        'image' => ['nullable', 'string'],
        'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
    ]);

    $cart = session('cart', []);
    $id = $data['id'];
    $quantity = (int) ($data['qty'] ?? 1);

    $cart[$id] = [
        'id' => $id,
        'name' => $data['name'],
        'branch' => $data['branch'],
        'price' => (float) $data['price'],
        'image' => $data['image'] ?? '',
        'qty' => min(99, (int) data_get($cart, $id.'.qty', 0) + $quantity),
    ];

    session(['cart' => $cart]);

    return redirect()->route('cart.index');
})->name('cart.store');
Route::patch('/cart/{id}', function (Request $request, string $id) {
    $data = $request->validate([
        'qty' => ['required', 'integer', 'min:0', 'max:99'],
    ]);

    $cart = session('cart', []);

    if ((int) $data['qty'] === 0) {
        unset($cart[$id]);
    } elseif (isset($cart[$id])) {
        $cart[$id]['qty'] = (int) $data['qty'];
    }

    session(['cart' => $cart]);

    return redirect()->route('cart.index');
})->name('cart.update');
Route::delete('/cart/{id}', function (string $id) {
    $cart = session('cart', []);
    unset($cart[$id]);
    session(['cart' => $cart]);

    return redirect()->route('cart.index');
})->name('cart.destroy');
Route::delete('/cart', function () {
    session()->forget('cart');

    return redirect()->route('cart.index');
})->name('cart.clear');
Route::get('/checkout', fn () => redirect()->route('orders.current')->with('open_checkout', true))->name('checkout');
Route::post('/orders', function (Request $request) {
    if (session('auth_role') !== 'user') {
        return redirect()->route('login')->with('error', 'Please login as user first.');
    }

    $data = $request->validate([
        'full_name' => ['required', 'string', 'max:120'],
        'hostel_block' => ['required', 'string', 'max:80'],
        'room_number' => ['required', 'string', 'max:40'],
        'contact_number' => ['required', 'string', 'max:40'],
        'payment_method' => ['required', 'in:cash_on_delivery'],
    ]);

    $items = collect(session('cart', []))->values();

    if ($items->isEmpty()) {
        return redirect()->route('orders.current')->with('error', 'Your cart is empty.');
    }

    $subtotal = $items->sum(fn (array $item) => (float) $item['price'] * (int) $item['qty']);
    $delivery = 15;
    $id = 'HE-'.now()->format('YmdHis');
    $orders = session('orders', []);
    $orders[$id] = [
        'id' => $id,
        'order_number' => $id,
        'foods' => $items->pluck('name')->implode(', '),
        'items' => $items->all(),
        'subtotal' => $subtotal,
        'delivery_fee' => $delivery,
        'total' => $subtotal + $delivery,
        'status' => 'pending',
        'delivery_status' => 'Preparing',
        'payment_method' => 'Cash on Delivery',
        'customer' => $data,
        'created_at' => now()->toDateTimeString(),
    ];

    session(['orders' => $orders]);
    session()->forget('cart');

    return redirect()->route('orders.current')->with('success', 'Order placed successfully!');
})->name('orders.store');
Route::get('/my-orders', fn () => $renderUser('pages.my-orders', 'My Orders'))->name('orders.current');
Route::get('/order-history', fn () => $renderUser('pages.order-history', 'Order History'))->name('orders.history');
Route::get('/profile', fn () => $renderUser('pages.profile', 'Profile'))->name('profile.show');
Route::get('/settings', fn () => $renderUser('pages.settings', 'Settings'))->name('settings.index');
Route::get('/admin/dashboard', fn () => $renderSuperAdmin('admin.dashboard', 'Admin Dashboard'))->name('admin.dashboard');
Route::get('/admin/stores/{branch}', function (string $branch) use ($sampleData, $renderAdmin, $storeDashboardData, $requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }

    $data = $sampleData();
    $selectedBranch = collect($data['branches'])->first(function (array $item) use ($branch): bool {
        return (string) ($item['id'] ?? '') === $branch || ($item['slug'] ?? '') === $branch;
    });

    if (! $selectedBranch) {
        abort(404);
    }

    if (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') !== (int) $selectedBranch['id']) {
        abort(403, 'Branch admins can only view their own store data.');
    }

    return $renderAdmin('admin.store-dashboard', $selectedBranch['name'].' Admin Dashboard', $storeDashboardData($data, $selectedBranch));
})->name('admin.store.dashboard');
Route::get('/admin/stores/{branch}/reports/sales-pdf', function (Request $request, string $branch) use ($sampleData, $storeDashboardData, $requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }

    $data = $sampleData();
    $selectedBranch = collect($data['branches'])->first(fn (array $item): bool => (string) ($item['id'] ?? '') === $branch || ($item['slug'] ?? '') === $branch);

    if (! $selectedBranch) {
        abort(404);
    }

    if (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') !== (int) $selectedBranch['id']) {
        abort(403, 'Branch admins can only export their own store reports.');
    }

    $report = $storeDashboardData($data, $selectedBranch);
    $from = $request->query('from', now()->startOfMonth()->toDateString());
    $to = $request->query('to', now()->toDateString());
    $topRows = collect($report['storeAnalytics']['topFoods'])
        ->map(fn (int $sold, string $food): string => "<tr><td>{$food}</td><td>{$sold}</td></tr>")
        ->implode('');
    $html = <<<HTML
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{$selectedBranch['name']} Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #17201b; }
        h1 { margin-bottom: 4px; }
        p { color: #66736d; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 24px 0; }
        .card { border: 1px solid #dfe8e1; border-radius: 8px; padding: 14px; }
        .card span { display: block; color: #66736d; font-weight: 700; }
        .card strong { display: block; margin-top: 8px; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { padding: 12px; border-bottom: 1px solid #dfe8e1; text-align: left; }
        @media print { button { display: none; } body { margin: 18px; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Print or Save as PDF</button>
    <h1>{$selectedBranch['name']} Sales Report</h1>
    <p>Date range: {$from} to {$to}</p>
    <div class="grid">
        <div class="card"><span>Total Revenue Today</span><strong>PHP{$report['storeAnalytics']['revenueToday']}</strong></div>
        <div class="card"><span>Total Revenue This Week</span><strong>PHP{$report['storeAnalytics']['revenueWeek']}</strong></div>
        <div class="card"><span>Total Revenue This Month</span><strong>PHP{$report['storeAnalytics']['revenueMonth']}</strong></div>
    </div>
    <h2>Top 5 Best Selling Foods</h2>
    <table><thead><tr><th>Food Item</th><th>Total Sold</th></tr></thead><tbody>{$topRows}</tbody></table>
    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 300));</script>
</body>
</html>
HTML;

    return response($html)->header('Content-Type', 'text/html');
})->name('admin.store.sales.pdf');
Route::get('/admin/stores/{branch}/reports/inventory-export', function (string $branch) use ($sampleData, $storeDashboardData, $requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }

    $data = $sampleData();
    $selectedBranch = collect($data['branches'])->first(fn (array $item): bool => (string) ($item['id'] ?? '') === $branch || ($item['slug'] ?? '') === $branch);

    if (! $selectedBranch) {
        abort(404);
    }

    if (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') !== (int) $selectedBranch['id']) {
        abort(403, 'Branch admins can only export their own inventory.');
    }

    $rows = $storeDashboardData($data, $selectedBranch)['storeInventory']
        ->map(fn (array $item): array => [$item['name'], $item['stock'], $item['status']])
        ->prepend(['Food Item', 'Stock Quantity', 'Status'])
        ->map(fn (array $row): string => collect($row)->map(fn ($value): string => '"'.str_replace('"', '""', (string) $value).'"')->implode(','))
        ->implode("\n");

    return response($rows)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="'.$selectedBranch['slug'].'-inventory-report.csv"');
})->name('admin.store.inventory.export');
Route::get('/admin/foods', fn () => $renderSuperAdmin('admin.foods', 'Food Management'))->name('admin.foods');
Route::get('/admin/orders', fn () => $renderSuperAdmin('admin.orders', 'Order Management'))->name('admin.orders');
Route::patch('/admin/orders/{order}/status', function (Request $request, string $order) use ($requireSuperAdmin) {
    if ($redirect = $requireSuperAdmin()) {
        return $redirect;
    }

    $validated = $request->validate([
        'delivery_status' => ['required', 'in:Pending,Preparing,Ready for Pickup,Completed,Cancelled'],
    ]);

    $orders = session('orders', []);

    if (isset($orders[$order])) {
        $orders[$order]['delivery_status'] = $validated['delivery_status'];
        $orders[$order]['status'] = strtolower($validated['delivery_status']);
        session(['orders' => $orders]);

        // Send email when order is Completed
        if ($validated['delivery_status'] === 'Completed') {
            $o        = $orders[$order];
            $email    = data_get($o, 'customer.email') ?? data_get($o, 'customer.full_name');
            $dbUser   = \App\Models\User::where('email', session('auth_email'))->first();
            // Try to find user by name if no email stored
            $userName = data_get($o, 'customer.full_name', 'Customer');
            $userEmail = data_get($o, 'customer.email');

            if ($userEmail) {
                $recipient = \App\Models\User::where('email', $userEmail)->first();
                if (! $recipient) {
                    // Build anonymous notifiable
                    $recipient = new class($userEmail, $userName) extends \Illuminate\Notifications\AnonymousNotifiable {
                        public string $name;
                        public function __construct(string $email, string $name) {
                            $this->name = $name;
                            $this->routes['mail'] = $email;
                        }
                    };
                }
                try {
                    $recipient->notify(new \App\Notifications\OrderCompletedNotification(
                        $o['order_number'] ?? $order,
                        $o['foods'] ?? 'Your order',
                        (float) ($o['total'] ?? 0),
                        $o['branch'] ?? 'HostelEats'
                    ));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Order completed email failed: ' . $e->getMessage());
                }
            }
        }
    }

    return redirect()->route('admin.orders')->with('success', 'Order status updated.');
})->name('admin.orders.status.session');
Route::get('/admin/branches', fn () => $renderSuperAdmin('admin.branches', 'Branch Management'))->name('admin.branches');
Route::get('/admin/users', fn () => $renderSuperAdmin('admin.users', 'User Management'))->name('admin.users');
Route::get('/admin/analytics', fn () => $renderSuperAdmin('admin.analytics', 'Analytics'))->name('admin.analytics');
Route::get('/admin/reports', fn () => $renderSuperAdmin('admin.reports', 'Reports'))->name('admin.reports');
Route::get('/admin/settings', fn () => $renderAdmin('admin.settings', 'Admin Settings'))->name('admin.settings');

// ─── DB-backed Role-Based Order Management ──────────────────────────────────
use App\Http\Controllers\Admin\AdminOrderController;

Route::prefix('/admin/customer-orders')->name('admin.orders.')->group(function () use ($requireAdmin) {
    Route::get('/', function (\Illuminate\Http\Request $req) use ($requireAdmin) {
        if ($redirect = $requireAdmin()) {
            return $redirect;
        }
        return app(AdminOrderController::class)->index($req);
    })->name('index');

    Route::patch('/{order}/update', function (\Illuminate\Http\Request $req, \App\Models\Order $order) use ($requireAdmin) {
        if ($redirect = $requireAdmin()) {
            return $redirect;
        }
        return app(AdminOrderController::class)->update($req, $order);
    })->name('update');

    Route::patch('/{order}/status', function (\Illuminate\Http\Request $req, \App\Models\Order $order) use ($requireAdmin) {
        if ($redirect = $requireAdmin()) {
            return $redirect;
        }
        return app(AdminOrderController::class)->updateStatus($req, $order);
    })->name('update.status');

    Route::delete('/{order}', function (\App\Models\Order $order) use ($requireAdmin) {
        if ($redirect = $requireAdmin()) {
            return $redirect;
        }
        // Only super admin can delete
        if (session('auth_admin_scope') !== 'super') {
            abort(403, 'Only Super Admins can delete orders.');
        }
        $order->delete();
        return back()->with('success', 'Order deleted.');
    })->name('destroy');
});

// Branch-specific DB orders (used inside store-dashboard)
Route::get('/admin/stores/{branchId}/orders', function (int $branchId) use ($requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }
    if (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') !== $branchId) {
        abort(403, 'You can only view your own branch orders.');
    }
    return app(AdminOrderController::class)->storeDashboardOrders($branchId);
})->name('admin.store.orders');

// Branch Customers
use App\Http\Controllers\Admin\BranchCustomerController;
Route::get('/admin/stores/{branchId}/customers', function (int $branchId) use ($requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }
    return app(BranchCustomerController::class)->index($branchId);
})->name('admin.store.customers');

// Super Admin: all customers (filterable by branch)
Route::get('/admin/customers', function (\Illuminate\Http\Request $req) use ($requireAdmin) {
    if ($redirect = $requireAdmin()) {
        return $redirect;
    }
    return app(BranchCustomerController::class)->all($req);
})->name('admin.customers');
