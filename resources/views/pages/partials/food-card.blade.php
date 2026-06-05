<article
    class="menu-card"
    data-category="{{ $food['category'] }}"
    data-searchable="{{ strtolower($food['name'].' '.$food['branch'].' '.$food['category'].' '.$food['tag']) }}"
    data-branch-url="{{ $food['branch_url'] ?? '/branches/'.($food['branch_id'] ?? strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $food['branch']), '-'))) }}"
    role="link"
    tabindex="0"
    aria-label="Open {{ $food['branch'] }} branch page for {{ $food['name'] }}"
>
    <div class="food-image-wrap">
        <img
            class="food-image"
            src="{{ $food['photo'] ?? $food['image'] }}"
            data-fallback-src="{{ $food['fallback_image'] ?? $food['image'] }}"
            alt="{{ $food['name'] }} from {{ $food['branch'] }}"
            loading="lazy"
            referrerpolicy="no-referrer"
        >
    </div>
    <div class="tag-row">
        <small>{{ $food['tag'] }}</small>
        <small>{{ $food['category'] }}</small>
    </div>
    <h3>{{ $food['name'] }}</h3>
    <p>{{ $food['branch'] }}</p>
    <strong>PHP{{ number_format($food['price'], 2) }}</strong>
    <div class="menu-card-actions">
        <a class="secondary-action view-branch-action" href="{{ $food['branch_url'] ?? '/branches/'.($food['branch_id'] ?? strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $food['branch']), '-'))) }}">
            View Branch
        </a>
        <button
            type="button"
            data-add-cart
            data-cart-id="{{ $food['id'] }}"
            data-cart-name="{{ $food['name'] }}"
            data-cart-branch="{{ $food['branch'] }}"
            data-cart-price="{{ $food['price'] }}"
            data-cart-image="{{ $food['photo'] ?? $food['image'] }}"
        >
            Add to Cart
        </button>
    </div>
</article>
