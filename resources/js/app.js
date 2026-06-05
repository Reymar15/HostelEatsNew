import './bootstrap';

const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
        return;
    }

    callback();
};

ready(() => {
    const body = document.body;
    const sidebar = document.querySelector('[data-sidebar]');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const searchInput = document.querySelector('[data-global-search]');
    const logoutModal = document.querySelector('[data-logout-modal]');
    const cartDrawer = document.querySelector('.cart-drawer');
    const checkoutModal = document.querySelector('[data-checkout-modal]');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let logoutUrl = '/logout';
    let latestCart = { items: [], count: 0, subtotal: 0, delivery: 0, total: 0 };

    const money = (value) => Number(value || 0).toFixed(2);

    const fetchCart = async (url = '/cart', options = {}) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
            },
            ...options,
        });

        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || 'Cart request failed.');
        }

        return payload;
    };

    const setCartCount = (count) => {
        document.querySelectorAll('[data-cart-badge]').forEach((badge) => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'grid' : 'none';
            badge.classList.remove('badge-bump');
            requestAnimationFrame(() => badge.classList.add('badge-bump'));
        });
    };

    const renderCartRows = (cart) => {
        latestCart = cart;
        const hasItems = cart.items.length > 0;

        document.querySelectorAll('[data-cart-list]').forEach((list) => {
            list.querySelectorAll('[data-cart-row]').forEach((row) => row.remove());

            cart.items.forEach((item) => {
                const row = document.createElement('div');
                row.className = 'cart-row';
                row.dataset.cartRow = 'true';
                row.innerHTML = `
                    <img src="${item.image || ''}" alt="${item.name}">
                    <div class="cart-row-body">
                        <div class="cart-row-main">
                            <h3>${item.name}</h3>
                            <p>${item.branch}</p>
                            <strong>PHP${money(Number(item.price) * Number(item.qty))}</strong>
                        </div>
                        <div class="cart-controls">
                            <button type="button" data-cart-decrease="${item.id}">-</button>
                            <span>${item.qty}</span>
                            <button type="button" data-cart-increase="${item.id}">+</button>
                            <button type="button" class="cart-remove-button" data-cart-remove="${item.id}">Remove</button>
                        </div>
                    </div>
                `;
                list.appendChild(row);
            });
        });

        document.querySelectorAll('[data-empty-cart]').forEach((node) => {
            node.toggleAttribute('hidden', hasItems);
        });

        document.querySelectorAll('[data-cart-summary]').forEach((node) => {
            node.hidden = !hasItems;
        });

        document.querySelectorAll('[data-cart-subtotal]').forEach((node) => {
            node.textContent = money(cart.subtotal);
        });

        document.querySelectorAll('[data-cart-delivery]').forEach((node) => {
            node.textContent = money(cart.delivery);
        });

        document.querySelectorAll('[data-cart-total], [data-checkout-total]').forEach((node) => {
            node.textContent = money(cart.total);
        });

        document.querySelectorAll('[data-open-checkout]').forEach((button) => {
            button.disabled = !hasItems;
        });

        const checkoutItems = document.querySelector('[data-checkout-items]');
        if (checkoutItems) {
            checkoutItems.innerHTML = cart.items.map((item) => `
                <div class="checkout-item-row">
                    <span>${item.name} x ${item.qty}</span>
                    <strong>PHP${money(Number(item.price) * Number(item.qty))}</strong>
                </div>
            `).join('');
        }

        setCartCount(cart.count);
    };

    const loadCart = async () => {
        renderCartRows(await fetchCart());
    };

    const openCart = (event) => {
        event?.preventDefault();
        body.classList.add('cart-drawer-open');
    };

    const closeCart = () => {
        body.classList.remove('cart-drawer-open');
    };

    const openCheckout = () => {
        if (latestCart.count === 0) return;
        checkoutModal?.classList.add('open');
        checkoutModal?.setAttribute('aria-hidden', 'false');
    };

    const closeCheckout = () => {
        checkoutModal?.classList.remove('open');
        checkoutModal?.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('[data-open-cart-sidebar]').forEach((item) => item.addEventListener('click', openCart));
    document.querySelectorAll('[data-close-cart-sidebar]').forEach((item) => item.addEventListener('click', closeCart));
    document.querySelectorAll('[data-open-checkout]').forEach((item) => item.addEventListener('click', openCheckout));
    document.querySelectorAll('[data-close-checkout]').forEach((item) => item.addEventListener('click', closeCheckout));

    cartDrawer?.addEventListener('click', (event) => event.stopPropagation());
    checkoutModal?.addEventListener('click', (event) => {
        if (event.target === checkoutModal) closeCheckout();
    });

    document.querySelectorAll('img[data-fallback-src]').forEach((image) => {
        image.addEventListener('error', () => {
            if (image.dataset.usedFallback === 'true') return;
            image.dataset.usedFallback = 'true';
            image.src = image.dataset.fallbackSrc;
        });
    });

    const interactiveSelector = 'a, button, input, select, textarea, label, [role="button"]';

    document.querySelectorAll('[data-branch-url]').forEach((card) => {
        const navigateToBranch = () => {
            const url = card.dataset.branchUrl;
            if (url) window.location.href = url;
        };

        card.addEventListener('click', (event) => {
            if (event.target.closest(interactiveSelector)) return;
            navigateToBranch();
        });

        card.addEventListener('keydown', (event) => {
            if (!['Enter', ' '].includes(event.key)) return;
            if (event.target.closest(interactiveSelector)) return;
            event.preventDefault();
            navigateToBranch();
        });
    });

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.closest('.password-field')?.querySelector('input');
            if (!input) return;

            const showPassword = input.type === 'password';
            input.type = showPassword ? 'text' : 'password';
            button.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
            button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
        });
    });

    document.querySelectorAll('[data-promo-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-promo-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-promo-dot]'));
        const previous = carousel.querySelector('[data-promo-prev]');
        const next = carousel.querySelector('[data-promo-next]');
        let current = 0;
        let timer = null;

        if (slides.length <= 1) return;

        const showSlide = (index) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === current;
                slide.classList.toggle('active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });
            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('active', dotIndex === current);
            });
        };

        const start = () => {
            stop();
            timer = window.setInterval(() => showSlide(current + 1), 3000);
        };

        const stop = () => {
            if (timer) window.clearInterval(timer);
        };

        previous?.addEventListener('click', () => {
            showSlide(current - 1);
            start();
        });

        next?.addEventListener('click', () => {
            showSlide(current + 1);
            start();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                start();
            });
        });

        carousel.addEventListener('mouseenter', stop);
        carousel.addEventListener('mouseleave', start);
        carousel.addEventListener('focusin', stop);
        carousel.addEventListener('focusout', start);

        start();
    });

    const updateBranchSectionVisibility = () => {
        document.querySelectorAll('.branch-menu-section').forEach((section) => {
            const cards = section.querySelectorAll('.menu-card');
            if (cards.length === 0) return;

            const hasVisibleCard = Array.from(cards).some((card) => {
                return !card.classList.contains('hidden-by-filter') && !card.classList.contains('hidden-by-search');
            });

            section.classList.remove('hidden-by-search');
            section.classList.toggle('hidden-by-filter', !hasVisibleCard);
        });
    };

    sidebarToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('open');
    });

    searchInput?.addEventListener('input', (event) => {
        const term = event.target.value.trim().toLowerCase();
        document.querySelectorAll('[data-searchable]').forEach((item) => {
            const haystack = item.dataset.searchable || '';
            item.classList.toggle('hidden-by-search', term !== '' && !haystack.includes(term));
        });
        updateBranchSectionVisibility();

        const adminResults = document.querySelector('[data-admin-global-results]');
        if (adminResults) {
            const groups = window.hostelAdminSearchIndex || {};
            const matches = Object.entries(groups).flatMap(([type, rows]) => {
                return Object.values(rows).map((row) => ({ type, ...row }));
            }).filter((row) => {
                return `${row.type} ${row.label} ${row.meta}`.toLowerCase().includes(term);
            }).slice(0, 8);

            adminResults.innerHTML = term && matches.length
                ? matches.map((row) => `
                    <a href="${row.route}">
                        <strong>${row.label}</strong>
                        <span>${row.type} · ${row.meta || 'HostelEats admin'}</span>
                    </a>
                `).join('')
                : (term ? '<p>No matching admin records.</p>' : '');
            adminResults.classList.toggle('open', term.length > 0);
        }
    });

    document.querySelectorAll('[data-add-cart]').forEach((button) => {
        button.addEventListener('click', async () => {
            const formData = new FormData();
            formData.append('id', button.dataset.cartId);
            formData.append('name', button.dataset.cartName);
            formData.append('branch', button.dataset.cartBranch);
            formData.append('price', button.dataset.cartPrice);
            formData.append('image', button.dataset.cartImage);
            formData.append('qty', '1');

            await fetchCart('/cart', { method: 'POST', body: formData });
            await loadCart();
            openCart();

            button.textContent = 'Added';
            setTimeout(() => {
                button.textContent = 'Add to Cart';
            }, 900);
        });
    });

    document.addEventListener('click', async (event) => {
        const increase = event.target.closest('[data-cart-increase]');
        const decrease = event.target.closest('[data-cart-decrease]');
        const remove = event.target.closest('[data-cart-remove]');

        if (!increase && !decrease && !remove) return;

        const id = increase?.dataset.cartIncrease || decrease?.dataset.cartDecrease || remove?.dataset.cartRemove;
        const item = latestCart.items.find((cartItem) => cartItem.id === id);

        if (remove) {
            renderCartRows(await fetchCart(`/cart/${id}`, { method: 'DELETE' }));
            return;
        }

        if (!item) return;

        const qty = increase ? Number(item.qty) + 1 : Number(item.qty) - 1;
        renderCartRows(await fetchCart(`/cart/${id}`, {
            method: 'PATCH',
            body: JSON.stringify({ qty: Math.max(0, qty) }),
        }));
    });

    document.querySelectorAll('[data-clear-cart]').forEach((button) => {
        button.addEventListener('click', async () => {
            renderCartRows(await fetchCart('/cart', { method: 'DELETE' }));
        });
    });

    document.querySelectorAll('[data-category-filter]').forEach((button) => {
        button.addEventListener('click', () => {
            const category = button.dataset.categoryFilter;
            document.querySelectorAll('[data-category-filter]').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');

            document.querySelectorAll('[data-category]').forEach((card) => {
                card.classList.toggle('hidden-by-filter', category !== 'All' && card.dataset.category !== category);
            });
            updateBranchSectionVisibility();
        });
    });

    document.querySelectorAll('[data-counter]').forEach((counter) => {
        const target = Number(counter.dataset.counter || 0);
        const duration = 900;
        const start = performance.now();
        const render = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const value = Math.round(target * (1 - Math.pow(1 - progress, 3)));
            counter.textContent = value.toLocaleString();
            if (progress < 1) requestAnimationFrame(render);
        };
        requestAnimationFrame(render);
    });

    const runAdminFilters = (tableId) => {
        const table = document.getElementById(tableId);
        if (!table) return;

        const search = document.querySelector(`[data-admin-search="${tableId}"]`)?.value.trim().toLowerCase() || '';
        const filters = Array.from(document.querySelectorAll(`[data-admin-filter="${tableId}"]`));

        table.querySelectorAll('tbody tr').forEach((row) => {
            const matchesSearch = !search || (row.dataset.searchable || '').includes(search);
            const matchesFilters = filters.every((filter) => {
                const value = filter.value;
                const key = filter.dataset.filterKey;
                return !value || row.dataset[key] === value;
            });
            row.classList.toggle('hidden-by-search', !matchesSearch || !matchesFilters);
        });
    };

    document.querySelectorAll('[data-admin-search]').forEach((input) => {
        input.addEventListener('input', () => runAdminFilters(input.dataset.adminSearch));
    });

    document.querySelectorAll('[data-admin-filter]').forEach((select) => {
        select.addEventListener('change', () => runAdminFilters(select.dataset.adminFilter));
    });

    document.querySelectorAll('[data-sort-table]').forEach((button) => {
        button.addEventListener('click', () => {
            const table = document.getElementById(button.dataset.sortTable);
            const tbody = table?.querySelector('tbody');
            if (!tbody) return;

            Array.from(tbody.querySelectorAll('tr'))
                .sort((a, b) => Number(b.dataset.date || 0) - Number(a.dataset.date || 0))
                .forEach((row) => tbody.appendChild(row));
        });
    });

    document.querySelectorAll('.status-select').forEach((select) => {
        select.addEventListener('change', () => {
            select.className = `status-select ${select.value.toLowerCase()}`;
        });
    });

    const openAdminModal = (id) => {
        const modal = document.querySelector(`[data-modal-id="${id}"]`);
        modal?.classList.add('open');
        modal?.setAttribute('aria-hidden', 'false');
    };

    const closeAdminModal = (modal) => {
        modal?.classList.remove('open');
        modal?.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('[data-admin-modal]').forEach((button) => {
        button.addEventListener('click', () => openAdminModal(button.dataset.adminModal));
    });

    document.querySelectorAll('[data-close-admin-modal]').forEach((button) => {
        button.addEventListener('click', () => closeAdminModal(button.closest('.admin-modal')));
    });

    document.querySelectorAll('.admin-modal').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeAdminModal(modal);
        });
    });

    document.querySelector('[data-admin-profile-toggle]')?.addEventListener('click', () => {
        document.querySelector('[data-admin-profile-menu]')?.classList.toggle('open');
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.admin-profile-menu')) {
            document.querySelector('[data-admin-profile-menu]')?.classList.remove('open');
        }
    });

    document.querySelectorAll('[data-fake-download]').forEach((button) => {
        button.addEventListener('click', () => {
            const toast = document.createElement('div');
            toast.className = 'auth-toast order-success-toast';
            toast.textContent = 'Report generated for this session demo.';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4200);
        });
    });

    document.querySelector('[data-theme-color]')?.addEventListener('input', (event) => {
        document.documentElement.style.setProperty('--brand', event.target.value);
    });

    if (window.Chart) {
        const chartPalette = ['#0f7c55', '#315f9d', '#f0b323', '#cf3e36', '#6b7280'];
        document.querySelectorAll('[data-chart]').forEach((canvas) => {
            const raw = JSON.parse(canvas.dataset.values || '[]');
            const isObject = !Array.isArray(raw);
            const labels = isObject ? Object.keys(raw) : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const values = isObject ? Object.values(raw) : raw;
            const chartType = canvas.dataset.chart;

            const barCharts = ['topFoods', 'topBranches', 'ordersStatus'];
            const doughnutCharts = ['inventoryStatus'];

            new window.Chart(canvas, {
                type: doughnutCharts.includes(chartType) ? 'doughnut' : (barCharts.includes(chartType) ? 'bar' : 'line'),
                data: {
                    labels,
                    datasets: [{
                        label: chartType.replace(/([A-Z])/g, ' $1'),
                        data: values,
                        borderColor: chartPalette[0],
                        backgroundColor: ['revenue', 'salesTrend'].includes(chartType) ? 'rgba(15, 124, 85, 0.14)' : chartPalette,
                        borderWidth: 3,
                        fill: ['revenue', 'salesTrend'].includes(chartType),
                        tension: 0.38,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    plugins: { legend: { display: false } },
                    scales: doughnutCharts.includes(chartType) ? {} : {
                        y: { beginAtZero: true, grid: { color: 'rgba(104, 116, 109, 0.14)' } },
                        x: { grid: { display: false } },
                    },
                },
            });
        });
    }

    const openLogout = (event) => {
        event?.preventDefault();
        logoutUrl = event?.currentTarget?.getAttribute('href') || logoutUrl;
        logoutModal?.classList.add('open');
        logoutModal?.setAttribute('aria-hidden', 'false');
    };

    const closeLogout = () => {
        logoutModal?.classList.remove('open');
        logoutModal?.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('[data-logout-link], [data-open-logout]').forEach((item) => {
        item.addEventListener('click', openLogout);
    });

    document.querySelector('[data-logout-cancel]')?.addEventListener('click', closeLogout);
    document.querySelector('[data-logout-confirm]')?.addEventListener('click', () => {
        closeLogout();
        window.location.href = logoutUrl;
    });

    logoutModal?.addEventListener('click', (event) => {
        if (event.target === logoutModal) closeLogout();
    });

    const darkToggle = document.querySelector('[data-dark-toggle]');
    const notificationToggle = document.querySelector('[data-notification-toggle]');

    if (localStorage.getItem('hostel-dark-mode') === 'true') {
        body.classList.add('dark-mode');
        if (darkToggle) darkToggle.checked = true;
    }

    darkToggle?.addEventListener('change', () => {
        body.classList.toggle('dark-mode', darkToggle.checked);
        localStorage.setItem('hostel-dark-mode', darkToggle.checked ? 'true' : 'false');
    });

    notificationToggle?.addEventListener('change', () => {
        alert(notificationToggle.checked ? 'Notifications enabled.' : 'Notifications muted.');
    });

    document.querySelector('[data-password-form]')?.addEventListener('submit', (event) => {
        event.preventDefault();
        const message = document.querySelector('[data-form-message]');
        if (message) {
            message.textContent = 'Password form submitted successfully for this frontend demo.';
        }
        event.target.reset();
    });

    document.querySelector('[data-signup-form]')?.addEventListener('submit', (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const error = form.querySelector('[data-auth-error]');
        const password = form.querySelector('input[name="password"]');
        const confirmPassword = form.querySelector('input[name="password_confirmation"]');
        const successModal = document.querySelector('[data-success-modal]');

        if (!form.checkValidity()) {
            if (error) error.textContent = 'Please complete all fields correctly.';
            form.reportValidity();
            return;
        }

        if (password.value !== confirmPassword.value) {
            if (error) error.textContent = 'Password and confirm password must match.';
            confirmPassword.focus();
            return;
        }

        if (error) error.textContent = '';
        form.reset();
        successModal?.classList.add('open');
        successModal?.setAttribute('aria-hidden', 'false');
    });

    document.querySelector('[data-success-close]')?.addEventListener('click', () => {
        const successModal = document.querySelector('[data-success-modal]');
        successModal?.classList.remove('open');
        successModal?.setAttribute('aria-hidden', 'true');
    });

    loadCart()
        .then(() => {
            if (document.querySelector('[data-auto-checkout]')) {
                openCheckout();
            }
        })
        .catch(() => setCartCount(0));
});
