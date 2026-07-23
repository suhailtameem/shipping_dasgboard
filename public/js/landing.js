/* ═══════════════════════════════════════════════════════════════════════════
   GLOBAL LOGISTICS & SMART SHIPPING LANDING PAGE - INTERACTIVE SCRIPT
   ═══════════════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {
    
    /* ── Sticky Navbar Blur on Scroll ── */
    const navbar = document.querySelector('.navbar-landing');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    /* ── Dark / Light Mode Intelligent Switcher ── */
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const themeToggleIcon = document.getElementById('themeToggleIcon');

    function updateThemeIcon(theme) {
        if (!themeToggleIcon) return;
        if (theme === 'dark') {
            themeToggleIcon.className = 'bi bi-sun-fill';
            themeToggleIcon.style.color = '#FF9500';
        } else {
            themeToggleIcon.className = 'bi bi-moon-stars-fill';
            themeToggleIcon.style.color = '';
        }
    }

    // Initialize icon state based on current document theme
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateThemeIcon(currentTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function () {
            const activeTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = activeTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('nexus_landing_theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    /* ── Counter Up Animation for Statistics Section ── */
    const statBox = document.querySelector('.stats-banner');
    let animated = false;

    function animateCounters() {
        const counters = document.querySelectorAll('.stat-count');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const suffix = counter.getAttribute('data-suffix') || '';
            const duration = 2000; // 2 seconds
            const stepTime = 30;
            const steps = duration / stepTime;
            const increment = target / steps;
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    counter.innerText = Math.floor(current).toLocaleString() + suffix;
                    clearInterval(timer);
                } else {
                    counter.innerText = Math.floor(current).toLocaleString() + suffix;
                }
            }, stepTime);
        });
    }

    if (statBox) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !animated) {
                animated = true;
                animateCounters();
            }
        }, { threshold: 0.3 });
        observer.observe(statBox);
    }

    /* ── Interactive Shipping Cost Calculator Logic ── */
    const calcBtn = document.getElementById('calcShippingBtn');
    const calcCostDisplay = document.getElementById('calcEstimatedCost');
    const calcBreakdown = document.getElementById('calcBreakdownText');
    const transportSelect = document.getElementById('calcTransport');
    const originSelect = document.getElementById('calcOrigin');
    const destSelect = document.getElementById('calcDest');
    const weightInput = document.getElementById('calcWeight');
    const categorySelect = document.getElementById('calcCategory');
    const currencySelect = document.getElementById('calcCurrency');

    function updateCalcDestinations(type) {
        if (!originSelect || !destSelect || !window.shippingCalcData) return;

        const data = window.shippingCalcData;
        const isAr = data.isAr;
        let list = [];

        if (type === '1' || type === 'air') {
            list = data.airDests || [];
        } else if (type === '2' || type === 'ocean' || type === 'sea') {
            list = data.seaDests || [];
        } else if (type === '3' || type === 'road' || type === 'land') {
            list = data.landDests || [];
        }

        originSelect.innerHTML = '';
        destSelect.innerHTML = '';

        if (list && list.length > 0) {
            list.forEach((item, index) => {
                const label = isAr ? (item.ar || item.destinations) : (item.destinations || item.ar);
                const optOrigin = document.createElement('option');
                optOrigin.value = item.id;
                optOrigin.textContent = label;
                originSelect.appendChild(optOrigin);

                const optDest = document.createElement('option');
                optDest.value = item.id;
                optDest.textContent = label;
                destSelect.appendChild(optDest);
            });

            // Default destination to second option if available
            if (destSelect.options.length > 1) {
                destSelect.selectedIndex = 1;
            }
        } else {
            // Fallback options
            const defaults = [
                { id: 'SA', name: 'Saudi Arabia (Jeddah / Dammam)' },
                { id: 'AE', name: 'UAE (Dubai / Jebel Ali)' },
                { id: 'US', name: 'United States (New York / LA)' },
                { id: 'DE', name: 'Germany (Hamburg / Frankfurt)' },
                { id: 'CN', name: 'China (Shanghai / Ningbo)' }
            ];
            defaults.forEach((item, idx) => {
                const opt1 = new Option(item.name, item.id);
                const opt2 = new Option(item.name, item.id);
                originSelect.appendChild(opt1);
                destSelect.appendChild(opt2);
            });
            if (destSelect.options.length > 1) {
                destSelect.selectedIndex = 3;
            }
        }
    }

    // Initialize destinations on load
    if (transportSelect) {
        updateCalcDestinations(transportSelect.value);
        transportSelect.addEventListener('change', function () {
            updateCalcDestinations(this.value);
            doCalculateShipping();
        });
    }

    function doCalculateShipping() {
        if (!calcCostDisplay) return;

        const transport = transportSelect ? transportSelect.value : '1';
        const from = originSelect ? originSelect.value : '';
        const to = destSelect ? destSelect.value : '';
        const weight = weightInput ? parseFloat(weightInput.value) || 10 : 10;
        const currencyId = currencySelect ? currencySelect.value : 1;
        const category = categorySelect ? categorySelect.value : '';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const url = (window.shippingCalcData && window.shippingCalcData.calcUrl) ? window.shippingCalcData.calcUrl : '/calculate-shipping-cost';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                shipping_type: transport,
                from: from,
                to: to,
                weight: weight,
                currency_id: currencyId,
                category: category
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.success) {
                calcCostDisplay.innerText = data.formatted || (data.symbol + ' ' + data.cost);
                if (calcBreakdown) {
                    const fromText = originSelect ? (originSelect.options[originSelect.selectedIndex]?.text || from) : from;
                    const toText = destSelect ? (destSelect.options[destSelect.selectedIndex]?.text || to) : to;
                    calcBreakdown.innerText = `Est. Freight (${weight}kg) • ${fromText} ➔ ${toText}`;
                }
            }
        })
        .catch(err => {
            console.log('Calc request error, using client fallback:', err);
            let rate = transport === '1' ? 10.5 : (transport === '2' ? 3.2 : 5.5);
            let cost = Math.max(45, weight * rate);
            calcCostDisplay.innerText = '$ ' + cost.toFixed(2);
        });
    }

    if (calcBtn) {
        calcBtn.addEventListener('click', function (e) {
            e.preventDefault();
            doCalculateShipping();
            calcBtn.classList.add('pulse');
            setTimeout(() => calcBtn.classList.remove('pulse'), 500);
        });
    }

    if (currencySelect) {
        currencySelect.addEventListener('change', doCalculateShipping);
    }
    if (weightInput) {
        weightInput.addEventListener('input', doCalculateShipping);
    }

    /* ── Interactive Shipment Tracking ── */
    const trackingForm = document.getElementById('trackingForm');
    const trackBtn = document.getElementById('btnTrackSearch');
    const trackInput = document.getElementById('inputTrackNumber');

    if (trackingForm) {
        trackingForm.addEventListener('submit', function () {
            if (trackBtn) {
                trackBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Searching...';
            }
        });
    }

    /* ── FAQ Accordion Toggle ── */
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const header = item.querySelector('.faq-header');
        if (header) {
            header.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all other items
                faqItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                });

                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });

    /* ── Dashboard Mockup Sidebar Tabs ── */
    const mockSidebarItems = document.querySelectorAll('.mockup-sidebar .sidebar-item');
    mockSidebarItems.forEach(item => {
        item.addEventListener('click', function () {
            mockSidebarItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

});
