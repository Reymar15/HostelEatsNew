<article
    class="menu-card"
    data-category="{{ $food['category'] }}"
    data-searchable="{{ strtolower($food['name'].' '.$food['branch'].' '.$food['category'].' '.$food['tag']) }}"
    data-branch-url="{{ $food['branch_url'] ?? '/branches/'.($food['branch_id'] ?? strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $food['branch']), '-'))) }}"
    role="link"
    tabindex="0"
    aria-label="Order {{ $food['name'] }} from {{ $food['branch'] }}"
>
    {{-- IMAGE: fixed 160px, object-fit cover --}}
    <div class="fc-img">
        <img
            src="{{ $food['photo'] ?? $food['image'] }}"
            data-fallback-src="{{ $food['fallback_image'] ?? $food['image'] }}"
            alt="{{ $food['name'] }}"
            loading="lazy"
            referrerpolicy="no-referrer"
        >
        <span class="fc-badge">{{ $food['category'] }}</span>
    </div>

    {{-- BODY: fixed rows via CSS grid --}}
    <div class="fc-body">

        {{-- ROW 1: category tag --}}
        <div class="fc-row fc-row-tag">
            <span class="fc-tag">{{ $food['tag'] }}</span>
        </div>

        {{-- ROW 2: food name, 2-line clamp --}}
        <div class="fc-row fc-row-name">
            <h3 class="fc-name">{{ $food['name'] }}</h3>
        </div>

        {{-- ROW 3: branch name --}}
        <div class="fc-row fc-row-branch">
            <p class="fc-branch">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                {{ $food['branch'] }}
            </p>
        </div>

        {{-- ROW 4: price --}}
        <div class="fc-row fc-row-price">
            <strong class="fc-price">&#8369;{{ number_format($food['price'], 2) }}</strong>
        </div>

    </div>

    {{-- ACTIONS: pinned bottom, 60px --}}
    <div class="fc-actions">
        <a
            class="fc-btn fc-btn-secondary"
            href="{{ $food['branch_url'] ?? '/branches/'.($food['branch_id'] ?? strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $food['branch']), '-'))) }}"
            title="Go to {{ $food['branch'] }}"
        >
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Branch
        </a>
        <button
            type="button"
            class="fc-btn fc-btn-primary"
            data-add-cart
            data-cart-id="{{ $food['id'] }}"
            data-cart-name="{{ $food['name'] }}"
            data-cart-branch="{{ $food['branch'] }}"
            data-cart-price="{{ $food['price'] }}"
            data-cart-image="{{ $food['photo'] ?? $food['image'] }}"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
            Add to Cart
        </button>
    </div>
</article>
