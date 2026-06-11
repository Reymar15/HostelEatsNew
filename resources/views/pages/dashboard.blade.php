@extends('layouts.app')

@section('content')

{{-- Carousel --}}
<div class="he-carousel" id="main-carousel">
    <div class="he-carousel-track" id="carousel-track">

        <div class="he-carousel-slide">
            <img class="he-slide-image" src="https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="" loading="eager">
            <div class="he-slide-content">
                <div class="he-slide-badge">🍔 Hot Deals</div>
                <h2>Crave it. Order it.<br>Eat it. Repeat.</h2>
                <p>Browse your favourite campus branches and order food delivered right to your hostel room.</p>
                <div class="he-slide-actions">
                    <a href="{{ route('menu.index') }}" class="primary-action">Browse Menu</a>
                    <a href="{{ route('branches.index') }}" class="secondary-action">View Branches</a>
                </div>
            </div>
        </div>

        <div class="he-carousel-slide">
            <img class="he-slide-image" src="https://images.pexels.com/photos/60616/fried-chicken-chicken-fried-crunchy-60616.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="" loading="lazy">
            <div class="he-slide-content">
                <div class="he-slide-badge">🍗 Jollibee</div>
                <h2>Chickenjoy is calling your name.</h2>
                <p>Filipino fast-food favorites — Chickenjoy, Jolly Spaghetti, and Burger Steak available now.</p>
                <div class="he-slide-actions">
                    <a href="{{ route('branches.show', 1) }}" class="primary-action">Order Now</a>
                </div>
            </div>
        </div>

        <div class="he-carousel-slide">
            <img class="he-slide-image" src="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="" loading="lazy">
            <div class="he-slide-content">
                <div class="he-slide-badge">☕ Starbucks</div>
                <h2>Fuel your study session.</h2>
                <p>Caramel Macchiato, Frappuccinos, and pastries — perfect for late-night study sessions.</p>
                <div class="he-slide-actions">
                    <a href="{{ route('branches.show', 5) }}" class="primary-action">Order Drinks</a>
                </div>
            </div>
        </div>

        <div class="he-carousel-slide">
            <img class="he-slide-image" src="https://images.pexels.com/photos/1583884/pexels-photo-1583884.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="" loading="lazy">
            <div class="he-slide-content">
                <div class="he-slide-badge">🍔 McDonald's</div>
                <h2>Big Mac. Big Flavor.</h2>
                <p>World-famous burgers, golden fries, and McFloat — order from McDonald's in seconds.</p>
                <div class="he-slide-actions">
                    <a href="{{ route('branches.show', 2) }}" class="primary-action">Order Now</a>
                </div>
            </div>
        </div>

        <div class="he-carousel-slide">
            <img class="he-slide-image" src="https://images.pexels.com/photos/1279330/pexels-photo-1279330.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="" loading="lazy">
            <div class="he-slide-content">
                <div class="he-slide-badge">🍗 Mang Inasal</div>
                <h2>Grilled to perfection.</h2>
                <p>Pecho Inasal, Java Rice, and Halo-Halo — authentic Pinoy grills for hostel students.</p>
                <div class="he-slide-actions">
                    <a href="{{ route('branches.show', 3) }}" class="primary-action">Order Inasal</a>
                </div>
            </div>
        </div>

    </div>

    <button class="he-carousel-btn prev" id="carousel-prev" aria-label="Previous">&#8592;</button>
    <button class="he-carousel-btn next" id="carousel-next" aria-label="Next">&#8594;</button>

    <div class="he-carousel-dots" id="carousel-dots">
        @for ($i = 0; $i < 5; $i++)
            <button class="{{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endfor
    </div>
</div>

{{-- Branch Cards --}}
<div class="section-head">
    <div>
        <h2>Pick a Branch</h2>
        <p>Five favorites. Always open for hostel orders.</p>
    </div>
    <a href="{{ route('branches.index') }}">View all →</a>
</div>

<section class="branch-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:14px;">
    @foreach ($branches as $branch)
        <a class="branch-card branch-card-link" href="{{ route('branches.show', $branch['id']) }}"
           style="display:flex;flex-direction:column;align-items:center;padding:20px 16px;text-align:center;text-decoration:none;color:inherit;border-radius:12px;border:2px solid #f3f4f6;background:#fff;transition:all 200ms ease;"
           onmouseover="this.style.borderColor='#f97316';this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgb(249 115 22 / 15%)'"
           onmouseout="this.style.borderColor='#f3f4f6';this.style.transform='';this.style.boxShadow=''">
            <div style="width:60px;height:60px;border-radius:50%;background:#fff7ed;border:2px solid #fed7aa;display:grid;place-items:center;margin-bottom:10px;">
                <img src="{{ $branch['logo'] }}" alt="{{ $branch['name'] }}" style="width:36px;height:36px;object-fit:contain;">
            </div>
            <strong style="font-size:.9rem;font-weight:900;display:block;margin-bottom:4px;">{{ $branch['name'] }}</strong>
            <small style="font-size:.75rem;color:#9ca3af;">{{ $branch['status'] }}</small>
        </a>
    @endforeach
</section>

{{-- Popular Menu --}}
<div class="section-head">
    <div>
        <h2>Popular Menu</h2>
        <p>Best-sellers from all partner branches.</p>
    </div>
    <a href="{{ route('menu.index') }}">View all →</a>
</div>

<section class="menu-grid">
    @foreach (array_slice($foods, 0, 6) as $food)
        @include('pages.partials.food-card', ['food' => $food])
    @endforeach
</section>

{{-- Promo Banner --}}
<section style="margin:32px 0;background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1px solid #fed7aa;border-radius:16px;padding:clamp(24px,4vw,40px);display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
    <div>
        <p style="font-size:.78rem;font-weight:800;color:#ea580c;text-transform:uppercase;letter-spacing:.08em;margin:0 0 8px;">🎉 Special Offer</p>
        <h2 style="font-size:clamp(1.4rem,3vw,2rem);font-weight:900;color:#1a1a1a;margin:0 0 8px;">Free Delivery on Your First Order!</h2>
        <p style="color:#6b7280;margin:0;font-size:.95rem;">Register now and enjoy free delivery on your first hostel food order.</p>
    </div>
    <a href="{{ route('signup') }}" class="primary-action" style="white-space:nowrap;flex-shrink:0;">Get Started →</a>
</section>

{{-- About Us anchor --}}
<section id="about" style="margin:32px 0 0;padding:40px;background:#fff;border:1px solid #f3f4f6;border-radius:16px;">
    <p style="font-size:.75rem;font-weight:800;color:#f97316;text-transform:uppercase;letter-spacing:.08em;margin:0 0 8px;">🏢 About Us</p>
    <h2 style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:900;margin:0 0 14px;">HostelEats — Built for Hostel Students</h2>
    <p style="color:#6b7280;line-height:1.7;max-width:720px;margin:0;">HostelEats is a campus food ordering platform designed specifically for hostel students. We partner with your favourite fast-food branches on campus so you can order meals without leaving your room. Browse the menu, add to cart, and get your food delivered to your hostel block — fast, easy, and affordable.</p>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const track  = document.getElementById('carousel-track');
    const dots   = document.querySelectorAll('#carousel-dots button');
    const total  = dots.length;
    let current  = 0;
    let timer;

    function go(idx) {
        current = (idx + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function autoPlay() { timer = setInterval(() => go(current + 1), 4500); }

    document.getElementById('carousel-prev').addEventListener('click', () => { clearInterval(timer); go(current - 1); autoPlay(); });
    document.getElementById('carousel-next').addEventListener('click', () => { clearInterval(timer); go(current + 1); autoPlay(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { clearInterval(timer); go(i); autoPlay(); }));

    autoPlay();
})();
</script>
@endpush
