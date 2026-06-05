<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HostelEats - Hostel Ordering System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $publicBase = rtrim(request()->getBasePath(), '/');
        $cssAsset = $manifest['resources/css/app.css']['file'] ?? null;
        $jsAsset = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if ($cssAsset)
        <link rel="stylesheet" href="{{ $publicBase }}/build/{{ $cssAsset }}">
    @endif
    @if ($jsAsset)
        <script type="module" src="{{ $publicBase }}/build/{{ $jsAsset }}"></script>
    @endif
</head>
<body>
    <div class="app-shell" data-hostel-eats>
        <aside class="sidebar">
            <a class="brand" href="#">
                <span class="brand-mark">
                    <span class="brand-mark__tray">HE</span>
                </span>
                <span>
                    <strong>HostelEats</strong>
                    <small>Hostel Ordering System</small>
                </span>
            </a>

            <nav class="nav">
                <a class="nav-link active" href="#home" aria-current="page">
                    <span class="icon">home</span> Dashboard
                </a>
                <span class="nav-label">Browse</span>
                <a class="nav-link" href="#menu"><span class="icon">grid</span> All Menu</a>
                <a class="nav-link" href="#branches"><span class="icon">pin</span> Branches</a>
                <a class="nav-link" href="#categories"><span class="icon">tag</span> Categories</a>
                <span class="nav-label">Orders</span>
                <a class="nav-link" href="#orders"><span class="icon">bag</span> My Orders</a>
                <a class="nav-link" href="#history"><span class="icon">clock</span> Order History</a>
                <span class="nav-label">Account</span>
                <a class="nav-link" href="#profile"><span class="icon">user</span> Profile</a>
                <a class="nav-link" href="#settings"><span class="icon">gear</span> Settings</a>
                <a class="nav-link" href="#logout"><span class="icon">exit</span> Logout</a>
            </nav>

            <section class="student-card" aria-label="Logged in student">
                <span class="avatar">JD</span>
                <span>
                    <strong>Juan Dela Cruz</strong>
                    <small>Hostel Student</small>
                </span>
            </section>
        </aside>

        <div class="workspace">
            <header class="topbar">
                <form class="search" role="search">
                    <input id="food-search" type="search" placeholder="Search for food, branch, etc..." autocomplete="off">
                    <span class="search-icon">search</span>
                </form>
                <button class="cart-pill" type="button" data-scroll-cart>
                    <span>cart</span>
                    <strong>Cart (<span data-cart-count>3</span>)</strong>
                    <em data-cart-badge>3</em>
                </button>
            </header>

            <main class="content" id="home">
                @php
                    $promoSlides = [
                        [
                            'kicker' => 'Burger Promo',
                            'title' => 'Stacked burgers for late-night cravings',
                            'description' => 'Juicy patties, melty cheese, toasted buns, and crispy sides made for hungry hostel nights.',
                            'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=1100&q=85',
                            'accent' => 'burger',
                        ],
                        [
                            'kicker' => 'Chicken Meals',
                            'title' => 'Crispy chicken meals delivered hot',
                            'description' => 'Golden fried chicken, rice combos, and saucy student favorites from your go-to branches.',
                            'image' => 'https://images.pexels.com/photos/60616/fried-chicken-chicken-fried-crunchy-60616.jpeg?auto=compress&cs=tinysrgb&w=1100',
                            'accent' => 'chicken',
                        ],
                        [
                            'kicker' => 'Pasta Favorites',
                            'title' => 'Comfort pasta for study breaks',
                            'description' => 'Sweet spaghetti, creamy bowls, and warm pasta plates ready when your schedule gets packed.',
                            'image' => 'https://images.pexels.com/photos/1279330/pexels-photo-1279330.jpeg?auto=compress&cs=tinysrgb&w=1100',
                            'accent' => 'pasta',
                        ],
                        [
                            'kicker' => 'Coffee & Drinks',
                            'title' => 'Coffee, refreshers, and chill drinks',
                            'description' => 'Iced coffee, blended drinks, and cool refreshers for early classes and long review nights.',
                            'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=1100&q=85',
                            'accent' => 'coffee',
                        ],
                        [
                            'kicker' => 'Combo Meals',
                            'title' => 'Combo meals that actually fill you up',
                            'description' => 'Fries, burgers, chicken, drinks, and value bundles for quick, satisfying hostel meals.',
                            'image' => 'https://images.pexels.com/photos/1583884/pexels-photo-1583884.jpeg?auto=compress&cs=tinysrgb&w=1100',
                            'accent' => 'combo',
                        ],
                    ];

                    $floatingFoods = [
                        ['name' => 'Burger', 'image' => 'https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg?auto=compress&cs=tinysrgb&w=300'],
                        ['name' => 'Chicken', 'image' => 'https://images.pexels.com/photos/106343/pexels-photo-106343.jpeg?auto=compress&cs=tinysrgb&w=300'],
                        ['name' => 'Pasta', 'image' => 'https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg?auto=compress&cs=tinysrgb&w=300'],
                        ['name' => 'Coffee', 'image' => 'https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=300'],
                        ['name' => 'Fries', 'image' => 'https://images.pexels.com/photos/1583884/pexels-photo-1583884.jpeg?auto=compress&cs=tinysrgb&w=300'],
                        ['name' => 'Drinks', 'image' => 'https://images.pexels.com/photos/1352278/pexels-photo-1352278.jpeg?auto=compress&cs=tinysrgb&w=300'],
                    ];
                @endphp

                <section class="hero" aria-labelledby="hero-title">
                    <div class="hero-copy">
                        <span class="eyebrow">For hungry hostel students</span>
                        <h1 id="hero-title">Crave it. Order it.<br>Eat it. Repeat.</h1>
                        <p>The five most-loved branches on one app, delivered straight to your hostel door.</p>
                        <div class="hero-actions">
                            <a href="#menu" class="primary-action">Browse Menu</a>
                            <a href="#branches" class="secondary-action">View Branches</a>
                        </div>
                    </div>
                    <div class="food-mosaic" aria-hidden="true">
                        <span>burger</span>
                        <span>fries</span>
                        <span>chicken</span>
                        <span>spaghetti</span>
                        <span>pizza</span>
                        <span>coffee</span>
                    </div>
                </section>

                <section class="promo-carousel" aria-label="HostelEats food promotions" data-promo-carousel>
                    <div class="promo-carousel-track">
                        @foreach ($promoSlides as $index => $slide)
                            <article class="promo-slide {{ $index === 0 ? 'active' : '' }} promo-slide-{{ $slide['accent'] }}" data-promo-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                                <div class="promo-slide-copy">
                                    <span class="promo-kicker">{{ $slide['kicker'] }}</span>
                                    <h2>{{ $slide['title'] }}</h2>
                                    <p>{{ $slide['description'] }}</p>
                                    <div class="promo-actions">
                                        <a href="{{ route('login') }}" class="primary-action">Order Now</a>
                                        <a href="#menu" class="secondary-action">View Menu</a>
                                    </div>
                                </div>
                                <div class="promo-image-wrap">
                                    <img src="{{ $slide['image'] }}" alt="{{ $slide['kicker'] }} food promo">
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="floating-food-cards" aria-hidden="true">
                        @foreach ($floatingFoods as $food)
                            <span class="floating-food-card">
                                <img src="{{ $food['image'] }}" alt="">
                                <strong>{{ $food['name'] }}</strong>
                            </span>
                        @endforeach
                    </div>

                    <button class="promo-control prev" type="button" data-promo-prev aria-label="Previous promo">&lsaquo;</button>
                    <button class="promo-control next" type="button" data-promo-next aria-label="Next promo">&rsaquo;</button>

                    <div class="promo-dots" role="tablist" aria-label="Promo slides">
                        @foreach ($promoSlides as $index => $slide)
                            <button type="button" class="{{ $index === 0 ? 'active' : '' }}" data-promo-dot="{{ $index }}" aria-label="Show {{ $slide['kicker'] }}"></button>
                        @endforeach
                    </div>
                </section>

                <section class="section-head" id="branches">
                    <div>
                        <h2>Pick a branch</h2>
                        <p>Five favorites. Always open.</p>
                    </div>
                    <a href="#branches">View all branches</a>
                </section>

                <section class="branch-grid">
                    @foreach ([
                        ['Jollibee', 'Filipino fast-food favorites', 'bee', 'pink'],
                        ["McDonald's", 'World-famous burgers and fries', 'fries', 'yellow'],
                        ['Mang Inasal', 'Grilled chicken and Pinoy classics', 'flame', 'green'],
                        ['KFC', "Finger lickin' good chicken", 'drumstick', 'red'],
                        ['Starbucks', 'Coffee, drinks and pastries', 'coffee', 'sage'],
                    ] as $branch)
                        <article class="branch-card {{ $branch[3] }}" data-searchable="{{ strtolower($branch[0] . ' ' . $branch[1]) }}">
                            <span class="branch-icon">{{ $branch[2] }}</span>
                            <h3>{{ $branch[0] }}</h3>
                            <p>{{ $branch[1] }}</p>
                            <strong>Open now</strong>
                        </article>
                    @endforeach
                </section>

                <section class="section-head" id="menu">
                    <div>
                        <h2>Popular Menu</h2>
                        <p>Best-sellers from your favorite branches.</p>
                    </div>
                    <a href="#menu">View all menu</a>
                </section>

                <section class="menu-grid" data-menu-list>
                    @foreach ([
                        ['Chickenjoy', 'Jollibee', 95, 'chicken'],
                        ['Jolly Spaghetti', 'Jollibee', 75, 'spaghetti'],
                        ['Burger Steak', 'Jollibee', 89, 'steak'],
                        ['Yum Burger', 'Jollibee', 45, 'burger'],
                        ['Palabok', 'Jollibee', 85, 'palabok'],
                        ['Big Mac', "McDonald's", 180, 'bigmac'],
                    ] as $item)
                        <article class="menu-card" data-searchable="{{ strtolower($item[0] . ' ' . $item[1]) }}">
                            <span class="food-art {{ $item[3] }}"></span>
                            <h3>{{ $item[0] }}</h3>
                            <p>{{ $item[1] }}</p>
                            <strong>PHP{{ number_format($item[2], 2) }}</strong>
                            <button type="button" data-add-item data-name="{{ $item[0] }}" data-branch="{{ $item[1] }}" data-price="{{ $item[2] }}" data-art="{{ $item[3] }}">Add</button>
                        </article>
                    @endforeach
                </section>
            </main>
        </div>

        <aside class="order-panel" id="cart">
            <section class="cart-card">
                <div class="cart-head">
                    <h2>Your Cart (<span data-cart-count>3</span>)</h2>
                    <button type="button" data-clear-cart>Clear</button>
                </div>
                <div class="cart-items" data-cart-items></div>
                <dl class="summary">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>PHP<span data-subtotal>250.00</span></dd>
                    </div>
                    <div>
                        <dt>Delivery Fee</dt>
                        <dd>PHP<span data-delivery>15.00</span></dd>
                    </div>
                    <div class="total">
                        <dt>Total</dt>
                        <dd>PHP<span data-total>265.00</span></dd>
                    </div>
                </dl>
                <button class="checkout" type="button">Proceed to Checkout</button>
            </section>

            <section class="delivery-card">
                <h2>Delivery Info</h2>
                <p><span>pin</span> Hostel Block C, Main Campus</p>
                <p><span>clock</span> Open daily: 7AM - 11PM</p>
            </section>
        </aside>
    </div>

    <footer class="site-footer">&copy; 2026 HostelEats - Hostel Ordering System</footer>
</body>
</html>
