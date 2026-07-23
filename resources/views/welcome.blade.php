@php
    $company = \App\Models\company::first();

    $currentLocale = app()->getLocale();
    $langParam = request()->route('lang') ?? request()->get('lang') ?? ($currentLocale == 'ar' ? 'Ar' : 'En');
    $isArabic = strtolower($langParam) == 'ar' || $currentLocale == 'ar';
    $langCode = $isArabic ? 'Ar' : 'En';
    $dir = $isArabic ? 'rtl' : 'ltr';
    $altLangCode = $isArabic ? 'En' : 'Ar';
    $altLangUrl = url('/' . $altLangCode . '/welcome');

    $companyName = $company ? ($isArabic ? ($company->name_ar ?: $company->name_en) : ($company->name_en ?: $company->name_ar)) : 'NEXUS LOGISTICS';
    $companyDesc = $company ? ($isArabic ? ($company->description_ar ?: $company->description_en) : ($company->description_en ?: $company->description_ar)) : __('landing.footer.brand_desc');
    $companyLogo = ($company && $company->logo) ? asset($company->logo) : null;
    $companyEmail = $company->email ?? null;
    $companyPhone = $company->phone ?? null;
    $companyWebsite = $company->website ?? null;
    $companyMapUrl = $company->google_map_url ?? null;
@endphp
<!DOCTYPE html>
<html lang="{{ $isArabic ? 'ar' : 'en' }}" dir="{{ $dir }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $companyName }} | {{ __('landing.hero.title') }} {{ __('landing.hero.accent_title') }}</title>
    
    <!-- Anti-Flash Theme Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('nexus_landing_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <!-- Meta Descriptions & SEO -->
    <meta name="description" content="{{ $companyDesc }}">

    <!-- Google Font Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <!-- Custom Theme Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- ════════════════════════ NAVIGATION BAR ════════════════════════ -->
    <nav class="navbar-landing">
        <div class="landing-container">
            <div class="nav-wrapper">
                <!-- Brand Logo -->
                <a href="{{ url('/' . $langCode . '/welcome') }}" class="brand-logo">
                    @if(!empty($companyLogo))
                        <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="max-height: 44px; max-width: 160px; object-fit: contain;">
                    @else
                        <div class="brand-logo-icon">
                            <i class="las la-shipping-fast"></i>
                        </div>
                    @endif
                    <span>{{ $companyName }}</span>
                </a>

                <!-- Nav Links -->
                <ul class="nav-links">
                    <li><a href="#services">{{ __('landing.nav.services') }}</a></li>
                    <li><a href="#containers">{{ __('landing.nav.containers') }}</a></li>
                    <li><a href="#calculator">{{ __('landing.nav.calculator') }}</a></li>
                    <li><a href="#tracking">{{ __('landing.nav.tracking') }}</a></li>
                    <li><a href="#features">{{ __('landing.nav.features') }}</a></li>
                    <li><a href="#fleet">{{ __('landing.nav.fleet') }}</a></li>
                    <li><a href="#faq">{{ __('landing.nav.faq') }}</a></li>
                </ul>

                <!-- Nav Actions -->
                <div class="nav-actions">
                    <!-- Dark/Light Theme Switcher Button -->
                    <button id="themeToggleBtn" class="lang-toggle-btn" type="button" aria-label="Toggle Theme" style="cursor: pointer;">
                        <i class="bi bi-moon-stars-fill" id="themeToggleIcon"></i>
                    </button>

                    <!-- Language Switcher Button -->
                    <a href="{{ $altLangUrl }}" class="lang-toggle-btn">
                        <i class="bi bi-globe"></i> {{ __('landing.nav.language') }}
                    </a>

                    <a href="{{ url('/' . $langCode . '/users/login') }}" class="btn-sec" style="padding: 10px 20px; font-size: 14px;">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('landing.nav.portal') }}
                    </a>
                    <a href="#calculator" class="btn-prime" style="padding: 10px 22px; font-size: 14px;">
                        {{ __('landing.nav.ship_now') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ════════════════════════ HERO SECTION ════════════════════════ -->
    <section class="hero-section">
        <div class="hero-bg-grid"></div>
        <div class="hero-bg-glow"></div>

        <div class="landing-container">
            <div class="hero-grid">
                <!-- Hero Left Content -->
                <div class="hero-content w-100">
                    <div class="section-tag">
                        <i class="bi bi-globe-americas"></i> {{ __('landing.hero.tag') }}
                    </div>
                    <h1>
                        {{ $companyName }}
                    </h1>

                    <p>
                        {{ __('landing.hero.subtitle') }}
                    </p>

                    <div class="hero-ctas">
                        <a href="#calculator" class="btn-prime btn-orange">
                            {{ __('landing.hero.ship_now') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i>
                        </a>
                        <a href="#tracking" class="btn-sec">
                            <i class="bi bi-geo-alt-fill" style="color: var(--primary);"></i> {{ __('landing.hero.track_shipment') }}
                        </a>
                    </div>

                    <div class="hero-trust-badges">
                        <div class="trust-item">
                            <i class="bi bi-check-circle-fill"></i> {{ __('landing.hero.trust_iso') }}
                        </div>
                        <div class="trust-item">
                            <i class="bi bi-check-circle-fill"></i> {{ __('landing.hero.trust_ports') }}
                        </div>
                        <div class="trust-item">
                            <i class="bi bi-check-circle-fill"></i> {{ __('landing.hero.trust_guarantee') }}
                        </div>
                    </div>
                </div>

                <!-- Hero Right Live Tracking Card Mockup -->
                <div class="hero-card-wrapper">
                    <!-- Floating Stat 1 -->
                    <div class="floating-stat-card top-right">
                        <div class="stat-icon">
                            <i class="las la-plane-departure"></i>
                        </div>
                        <div class="stat-text">
                            <h6>{{ __('landing.hero.air_express') }}</h6>
                            <p>{{ __('landing.hero.flight_status') }}</p>
                        </div>
                    </div>

                    <!-- Main Glass Card -->
                    <div class="hero-glass-card">
                        <div class="hero-card-header">
                            <div>
                                <small style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Tracking Number</small>
                                <h3 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin-top: 2px;">{{ __('landing.hero.tracking_num') }}</h3>
                            </div>
                            <div class="tracking-badge">
                                <span></span> {{ __('landing.hero.live_radar') }}
                            </div>
                        </div>

                        <div class="tracking-route">
                            <div class="route-point">
                                <h4>JED</h4>
                                <p>Saudi Arabia</p>
                            </div>
                            <div class="route-line">
                                <div class="route-line-icon">
                                    <i class="las la-ship"></i>
                                </div>
                            </div>
                            <div class="route-point" style="{{ $isArabic ? 'text-align: left;' : 'text-align: right;' }}">
                                <h4>HAM</h4>
                                <p>Germany</p>
                            </div>
                        </div>

                        <div class="tracking-timeline">
                            <div class="timeline-step">
                                <div class="step-icon active"><i class="bi bi-check"></i></div>
                                <div class="step-info">
                                    <h5>{{ __('landing.hero.departed') }}</h5>
                                    <p>{{ __('landing.hero.departed_sub') }}</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="step-icon active"><i class="bi bi-check"></i></div>
                                <div class="step-info">
                                    <h5>{{ __('landing.hero.customs_cleared') }}</h5>
                                    <p>{{ __('landing.hero.customs_sub') }}</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="step-icon"><i class="bi bi-circle"></i></div>
                                <div class="step-info">
                                    <h5>{{ __('landing.hero.estimated_arrival') }}</h5>
                                    <p>{{ __('landing.hero.estimated_sub') }}</p>
                                </div>
                            </div>
                        </div>

                        <div style="background: #F5F5F7; border-radius: var(--radius-md); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); font-size: 11px;">{{ __('landing.hero.container_id') }}</small>
                                <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">MSCU-7489201 (40ft HC)</div>
                            </div>
                            <div style="{{ $isArabic ? 'text-align: left;' : 'text-align: right;' }}">
                                <small style="color: var(--text-muted); font-size: 11px;">{{ __('landing.hero.weight') }}</small>
                                <div style="font-size: 14px; font-weight: 700; color: var(--accent-orange);">18,450 kg</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Stat 2 -->
                    <div class="floating-stat-card bottom-left">
                        <div class="stat-icon" style="background: rgba(52, 199, 89, 0.12); color: var(--accent-green);">
                            <i class="las la-shield-alt"></i>
                        </div>
                        <div class="stat-text">
                            <h6>{{ __('landing.hero.full_insurance') }}</h6>
                            <p>{{ __('landing.hero.insurance_sub') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ SERVICES SECTION ════════════════════════ -->
    <section class="section-padding" id="services">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-boxes"></i> {{ __('landing.services.tag') }}</div>
                <h2 class="section-title">{{ __('landing.services.title') }} <span class="gradient-accent">{{ __('landing.services.accent_title') }}</span></h2>
                <p class="section-subtitle">
                    {{ __('landing.services.subtitle') }}
                </p>
            </div>

            <div class="services-grid">
                <!-- Air Freight -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="las la-plane-departure"></i>
                    </div>
                    <h3>{{ __('landing.services.air_title') }}</h3>
                    <p>{{ __('landing.services.air_desc') }}</p>
                    <a href="#calculator" class="service-link">{{ __('landing.services.air_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Ocean Freight -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(255, 149, 0, 0.12); color: var(--accent-orange); border-color: rgba(255, 149, 0, 0.25);">
                        <i class="las la-ship"></i>
                    </div>
                    <h3>{{ __('landing.services.ocean_title') }}</h3>
                    <p>{{ __('landing.services.ocean_desc') }}</p>
                    <a href="#calculator" class="service-link" style="color: var(--accent-orange);">{{ __('landing.services.ocean_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Road Freight -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(48, 176, 199, 0.12); color: var(--accent-cyan); border-color: rgba(48, 176, 199, 0.25);">
                        <i class="las la-truck-moving"></i>
                    </div>
                    <h3>{{ __('landing.services.road_title') }}</h3>
                    <p>{{ __('landing.services.road_desc') }}</p>
                    <a href="#calculator" class="service-link" style="color: var(--accent-cyan);">{{ __('landing.services.road_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Warehousing -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(52, 199, 89, 0.12); color: var(--accent-green); border-color: rgba(52, 199, 89, 0.25);">
                        <i class="las la-warehouse"></i>
                    </div>
                    <h3>{{ __('landing.services.warehousing_title') }}</h3>
                    <p>{{ __('landing.services.warehousing_desc') }}</p>
                    <a href="#" class="service-link" style="color: var(--accent-green);">{{ __('landing.services.warehousing_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Cargo Consolidation -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="las la-dolly"></i>
                    </div>
                    <h3>{{ __('landing.services.consolidation_title') }}</h3>
                    <p>{{ __('landing.services.consolidation_desc') }}</p>
                    <a href="#" class="service-link">{{ __('landing.services.consolidation_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Customs Clearance -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(255, 149, 0, 0.12); color: var(--accent-orange); border-color: rgba(255, 149, 0, 0.25);">
                        <i class="las la-passport"></i>
                    </div>
                    <h3>{{ __('landing.services.customs_title') }}</h3>
                    <p>{{ __('landing.services.customs_desc') }}</p>
                    <a href="#" class="service-link" style="color: var(--accent-orange);">{{ __('landing.services.customs_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Shipping Documentation -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(48, 176, 199, 0.12); color: var(--accent-cyan); border-color: rgba(48, 176, 199, 0.25);">
                        <i class="las la-file-invoice-dollar"></i>
                    </div>
                    <h3>{{ __('landing.services.docs_title') }}</h3>
                    <p>{{ __('landing.services.docs_desc') }}</p>
                    <a href="#" class="service-link" style="color: var(--accent-cyan);">{{ __('landing.services.docs_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>

                <!-- Cargo Insurance -->
                <div class="service-card">
                    <div class="service-icon-box" style="background: rgba(52, 199, 89, 0.12); color: var(--accent-green); border-color: rgba(52, 199, 89, 0.25);">
                        <i class="las la-shield-alt"></i>
                    </div>
                    <h3>{{ __('landing.services.insurance_title') }}</h3>
                    <p>{{ __('landing.services.insurance_desc') }}</p>
                    <a href="#" class="service-link" style="color: var(--accent-green);">{{ __('landing.services.insurance_btn') }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i></a>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ CONTAINER TYPES SECTION ════════════════════════ -->
    <section class="section-padding bg-navy-alt" id="containers">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-box-seam"></i> {{ __('landing.containers.tag') }}</div>
                <h2 class="section-title">{{ __('landing.containers.title') }} <span class="gradient-orange">{{ __('landing.containers.accent_title') }}</span></h2>
                <p class="section-subtitle">
                    {{ __('landing.containers.subtitle') }}
                </p>
            </div>

            <div class="containers-grid">
                <!-- 20ft Container -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-box container-svg-icon"></i>
                    </div>
                    <span class="container-tag">{{ __('landing.containers.dry_cargo') }}</span>
                    <h4>{{ __('landing.containers.std_20') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>5.90 m (19.4 ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>33.2 CBM (1,172 cu ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>28,200 kg (62,170 lbs)</strong></div>
                    </div>
                </div>

                <!-- 40ft Container -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-boxes container-svg-icon" style="color: var(--primary);"></i>
                    </div>
                    <span class="container-tag" style="background: rgba(0, 113, 227, 0.12); color: var(--primary);">{{ __('landing.containers.volume_standard') }}</span>
                    <h4>{{ __('landing.containers.std_40') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>12.03 m (39.5 ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>67.7 CBM (2,390 cu ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>26,700 kg (58,860 lbs)</strong></div>
                    </div>
                </div>

                <!-- 40ft High Cube -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-layer-group container-svg-icon" style="color: var(--accent-cyan);"></i>
                    </div>
                    <span class="container-tag" style="background: rgba(48, 176, 199, 0.12); color: var(--accent-cyan);">{{ __('landing.containers.extra_height') }}</span>
                    <h4>{{ __('landing.containers.hc_40') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>12.03 m (39.5 ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>76.4 CBM (2,698 cu ft)</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>26,580 kg (58,600 lbs)</strong></div>
                    </div>
                </div>

                <!-- Reefer Container -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-snowflake container-svg-icon" style="color: var(--accent-green);"></i>
                    </div>
                    <span class="container-tag" style="background: rgba(52, 199, 89, 0.12); color: var(--accent-green);">{{ __('landing.containers.refrigerated') }}</span>
                    <h4>{{ __('landing.containers.reefer') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>5.45 m / 11.58 m</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>28.3 CBM / 67.3 CBM</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>27,400 kg</strong></div>
                    </div>
                </div>

                <!-- Open Top -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-truck-loading container-svg-icon" style="color: var(--accent-orange);"></i>
                    </div>
                    <span class="container-tag" style="background: rgba(255, 149, 0, 0.12); color: var(--accent-orange);">{{ __('landing.containers.top_loading') }}</span>
                    <h4>{{ __('landing.containers.open_top') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>5.89 m / 12.02 m</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>32.5 CBM / 65.8 CBM</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>28,100 kg</strong></div>
                    </div>
                </div>

                <!-- Flat Rack Container -->
                <div class="container-card">
                    <div class="container-visual">
                        <i class="las la-tools container-svg-icon" style="color: #AF52DE;"></i>
                    </div>
                    <span class="container-tag" style="background: rgba(175, 82, 222, 0.12); color: #AF52DE;">{{ __('landing.containers.heavy_breakbulk') }}</span>
                    <h4>{{ __('landing.containers.flat_rack') }}</h4>
                    <div class="container-specs">
                        <div class="spec-row"><span>{{ __('landing.containers.length') }}</span> <strong>5.94 m / 12.08 m</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.capacity') }}</span> <strong>Flush Folding Ends</strong></div>
                        <div class="spec-row"><span>{{ __('landing.containers.payload') }}</span> <strong>45,000 kg (99,200 lbs)</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ SHIPPING COST CALCULATOR ════════════════════════ -->
    <section class="section-padding calc-section-bg" id="calculator">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-calculator-fill"></i> {{ __('landing.calculator.tag') }}</div>
                <h2 class="section-title">{{ __('landing.calculator.title') }} <span class="gradient-accent">{{ __('landing.calculator.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.calculator.subtitle') }}</p>
            </div>

            <x-shipping-cost-caculator />
        </div>
    </section>


    <!-- ════════════════════════ LIVE SHIPMENT TRACKING INTERFACE ════════════════════════ -->
    <section class="section-padding" id="tracking">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-radar"></i> {{ __('landing.tracking.tag') }}</div>
                <h2 class="section-title">{{ __('landing.tracking.title') }} <span class="gradient-accent">{{ __('landing.tracking.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.tracking.subtitle') }}</p>
            </div>

            <div class="tracking-map-wrapper">
                <!-- Search Input Bar -->
                <div class="tracking-search-bar">
                    <input type="text" class="tracking-input" id="inputTrackNumber" value="NEX-8894021-SA" placeholder="{{ __('landing.tracking.placeholder') }}">
                    <button class="btn-prime" id="btnTrackSearch">
                        <i class="bi bi-search"></i> {{ __('landing.tracking.btn') }}
                    </button>
                </div>

                <!-- Status Header -->
                <div style="text-align: center; margin-bottom: 32px;">
                    <span id="trackStatusText" style="font-size: 18px; font-weight: 700; color: var(--accent-green);">
                        {{ __('landing.tracking.status_active') }}
                    </span>
                </div>

                <!-- Full Stepper Timeline -->
                <div class="tracking-stepper-full">
                    <div class="tracking-progress-fill" id="trackingProgressFill"></div>

                    <div class="stepper-node completed">
                        <div class="node-dot"><i class="bi bi-box"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_received') }}</div>
                    </div>

                    <div class="stepper-node completed">
                        <div class="node-dot"><i class="bi bi-warehouse"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_processing') }}</div>
                    </div>

                    <div class="stepper-node completed">
                        <div class="node-dot"><i class="bi bi-shield-check"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_customs') }}</div>
                    </div>

                    <div class="stepper-node active">
                        <div class="node-dot"><i class="bi bi-airplane"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_transit') }}</div>
                    </div>

                    <div class="stepper-node">
                        <div class="node-dot"><i class="bi bi-building-check"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_arrived') }}</div>
                    </div>

                    <div class="stepper-node">
                        <div class="node-dot"><i class="bi bi-truck"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_delivery') }}</div>
                    </div>

                    <div class="stepper-node">
                        <div class="node-dot"><i class="bi bi-check-circle"></i></div>
                        <div class="node-label">{{ __('landing.tracking.step_delivered') }}</div>
                    </div>
                </div>

                <!-- Map Canvas Representation -->
                <div class="map-canvas-container">
                    <svg width="100%" height="100%" viewBox="0 0 1000 320" preserveAspectRatio="none" style="position: absolute; inset: 0;">
                        <!-- SVG World Map Graphic Grid Lines -->
                        <path d="M 100 100 Q 300 20 600 120 T 900 80" stroke="rgba(0, 113, 227, 0.4)" stroke-width="3" fill="none" stroke-dasharray="6,6"/>
                        <circle cx="100" cy="100" r="8" fill="#0071E3"/>
                        <circle cx="600" cy="120" r="10" fill="#FF9500" style="animation: pulse 1.5s infinite;"/>
                        <circle cx="900" cy="80" r="8" fill="#34C759"/>

                        <text x="90" y="130" fill="#1D1D1F" font-size="13" font-weight="700">{{ __('landing.tracking.origin_loc') }}</text>
                        <text x="570" y="155" fill="#FF9500" font-size="14" font-weight="800">{{ __('landing.tracking.current_loc') }}</text>
                        <text x="860" y="110" fill="#1D1D1F" font-size="13" font-weight="700">{{ __('landing.tracking.dest_loc') }}</text>
                    </svg>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ FEATURES SECTION ════════════════════════ -->
    <section class="section-padding bg-navy-alt" id="features">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-cpu"></i> {{ __('landing.features.tag') }}</div>
                <h2 class="section-title">{{ __('landing.features.title') }} <span class="gradient-accent">{{ __('landing.features.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.features.subtitle') }}</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-radar"></i></div>
                    <h4>{{ __('landing.features.tracking_title') }}</h4>
                    <p>{{ __('landing.features.tracking_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-file-invoice"></i></div>
                    <h4>{{ __('landing.features.invoices_title') }}</h4>
                    <p>{{ __('landing.features.invoices_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-signature"></i></div>
                    <h4>{{ __('landing.features.proof_title') }}</h4>
                    <p>{{ __('landing.features.proof_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-credit-card"></i></div>
                    <h4>{{ __('landing.features.payments_title') }}</h4>
                    <p>{{ __('landing.features.payments_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-boxes"></i></div>
                    <h4>{{ __('landing.features.wms_title') }}</h4>
                    <p>{{ __('landing.features.wms_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-truck"></i></div>
                    <h4>{{ __('landing.features.fleet_title') }}</h4>
                    <p>{{ __('landing.features.fleet_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-chart-bar"></i></div>
                    <h4>{{ __('landing.features.analytics_title') }}</h4>
                    <p>{{ __('landing.features.analytics_desc') }}</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon"><i class="las la-headset"></i></div>
                    <h4>{{ __('landing.features.support_title') }}</h4>
                    <p>{{ __('landing.features.support_desc') }}</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ STATISTICS SECTION ════════════════════════ -->
    <section class="section-padding">
        <div class="landing-container">
            <div class="stats-banner">
                <div class="stat-box">
                    <h2 class="stat-count" data-target="15000" data-suffix="+">15,000+</h2>
                    <p>{{ __('landing.stats.shipments') }}</p>
                </div>

                <div class="stat-box">
                    <h2 class="stat-count" data-target="95" data-suffix="+">95+</h2>
                    <p>{{ __('landing.stats.countries') }}</p>
                </div>

                <div class="stat-box">
                    <h2 class="stat-count" data-target="99" data-suffix="%">99%</h2>
                    <p>{{ __('landing.stats.ontime') }}</p>
                </div>

                <div class="stat-box">
                    <h2 class="stat-count" data-target="24" data-suffix="/7">24/7</h2>
                    <p>{{ __('landing.stats.support') }}</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ FLEET SHOWCASE ════════════════════════ -->
    <section class="section-padding bg-navy-alt" id="fleet">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-truck-front"></i> {{ __('landing.fleet.tag') }}</div>
                <h2 class="section-title">{{ __('landing.fleet.title') }} <span class="gradient-orange">{{ __('landing.fleet.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.fleet.subtitle') }}</p>
            </div>

            <div class="fleet-grid">
                <!-- Cargo Aircraft -->
                <div class="fleet-card">
                    <div class="fleet-img-wrapper">
                        <i class="las la-plane-departure"></i>
                        <span class="fleet-badge">{{ __('landing.fleet.air_badge') }}</span>
                    </div>
                    <div class="fleet-info">
                        <h4>{{ __('landing.fleet.air_title') }}</h4>
                        <p>{{ __('landing.fleet.air_desc') }}</p>
                        <div class="fleet-tags">
                            <span class="fleet-tag-item">Capacity: 102 Tons</span>
                            <span class="fleet-tag-item">Range: 9,200 km</span>
                        </div>
                    </div>
                </div>

                <!-- Container Ship -->
                <div class="fleet-card">
                    <div class="fleet-img-wrapper">
                        <i class="las la-ship"></i>
                        <span class="fleet-badge">{{ __('landing.fleet.ship_badge') }}</span>
                    </div>
                    <div class="fleet-info">
                        <h4>{{ __('landing.fleet.ship_title') }}</h4>
                        <p>{{ __('landing.fleet.ship_desc') }}</p>
                        <div class="fleet-tags">
                            <span class="fleet-tag-item">Capacity: 18,000 TEU</span>
                            <span class="fleet-tag-item">Eco-Speed Hull</span>
                        </div>
                    </div>
                </div>

                <!-- Semi Trucks -->
                <div class="fleet-card">
                    <div class="fleet-img-wrapper">
                        <i class="las la-truck-moving"></i>
                        <span class="fleet-badge">{{ __('landing.fleet.truck_badge') }}</span>
                    </div>
                    <div class="fleet-info">
                        <h4>{{ __('landing.fleet.truck_title') }}</h4>
                        <p>{{ __('landing.fleet.truck_desc') }}</p>
                        <div class="fleet-tags">
                            <span class="fleet-tag-item">Payload: 34 Tons</span>
                            <span class="fleet-tag-item">Euro 6 Low Emission</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ CUSTOMER DASHBOARD PREVIEW ════════════════════════ -->
    <section class="section-padding">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-display"></i> {{ __('landing.portal.tag') }}</div>
                <h2 class="section-title">{{ __('landing.portal.title') }} <span class="gradient-accent">{{ __('landing.portal.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.portal.subtitle') }}</p>
            </div>

            <div class="dashboard-mockup-wrapper">
                <div class="mockup-header-bar">
                    <div class="dots-group">
                        <span class="dot-btn dot-red"></span>
                        <span class="dot-btn dot-yellow"></span>
                        <span class="dot-btn dot-green"></span>
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
                        https://nexus-logistics.app/{{ strtolower($langCode) }}/dashboard
                    </div>
                    <div><i class="bi bi-shield-lock-fill text-success"></i></div>
                </div>

                <div class="mockup-body">
                    <!-- Mockup Sidebar -->
                    <div class="mockup-sidebar">
                        <div class="sidebar-item active"><i class="bi bi-speedometer2"></i> {{ __('landing.portal.dash') }}</div>
                        <div class="sidebar-item"><i class="bi bi-box-seam"></i> {{ __('landing.portal.shipments') }}</div>
                        <div class="sidebar-item"><i class="bi bi-file-earmark-text"></i> {{ __('landing.portal.invoices') }}</div>
                        <div class="sidebar-item"><i class="bi bi-currency-exchange"></i> {{ __('landing.portal.rates') }}</div>
                        <div class="sidebar-item"><i class="bi bi-gear"></i> {{ __('landing.portal.settings') }}</div>
                    </div>

                    <!-- Mockup Content -->
                    <div class="mockup-content">
                        <div class="mockup-stat-grid">
                            <div class="mock-stat-card">
                                <h6>{{ __('landing.portal.active_units') }}</h6>
                                <h3 style="color: var(--primary);">24 Cargo Units</h3>
                            </div>
                            <div class="mock-stat-card">
                                <h6>{{ __('landing.portal.total_spend') }}</h6>
                                <h3 style="color: var(--accent-orange);">$48,920.00</h3>
                            </div>
                            <div class="mock-stat-card">
                                <h6>{{ __('landing.portal.customs_rate') }}</h6>
                                <h3 style="color: var(--accent-green);">100% Passed</h3>
                            </div>
                        </div>

                        <!-- Mock Table -->
                        <div style="background: #FFFFFF; border-radius: var(--radius-md); padding: 16px; border: 1px solid var(--border-light);">
                            <h5 style="font-size: 15px; margin-bottom: 12px; color: var(--text-main);">{{ __('landing.portal.recent_requests') }}</h5>
                            <table class="mock-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('landing.portal.req_id') }}</th>
                                        <th>{{ __('landing.portal.freight_type') }}</th>
                                        <th>{{ __('landing.portal.route') }}</th>
                                        <th>{{ __('landing.portal.weight') }}</th>
                                        <th>{{ __('landing.portal.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#REQ-89042</td>
                                        <td>Air Freight</td>
                                        <td>Jeddah -> Frankfurt</td>
                                        <td>450 kg</td>
                                        <td><span class="tracking-badge" style="padding: 2px 8px; font-size: 11px;">In Transit</span></td>
                                    </tr>
                                    <tr>
                                        <td>#REQ-89039</td>
                                        <td>Ocean Freight</td>
                                        <td>Shanghai -> Dammam</td>
                                        <td>18,200 kg</td>
                                        <td><span class="tracking-badge" style="padding: 2px 8px; font-size: 11px; background: rgba(0,113,227,0.12); color: var(--primary); border-color: rgba(0,113,227,0.25);">Processing</span></td>
                                    </tr>
                                    <tr>
                                        <td>#REQ-89012</td>
                                        <td>Road Freight</td>
                                        <td>Riyadh -> Dubai</td>
                                        <td>1,200 kg</td>
                                        <td><span class="tracking-badge" style="padding: 2px 8px; font-size: 11px;">Delivered</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ TESTIMONIALS ════════════════════════ -->
    <section class="section-padding bg-navy-alt">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-star-fill"></i> {{ __('landing.testimonials.tag') }}</div>
                <h2 class="section-title">{{ __('landing.testimonials.title') }} <span class="gradient-orange">{{ __('landing.testimonials.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.testimonials.subtitle') }}</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p>{{ __('landing.testimonials.review1') }}</p>
                    <div class="client-info">
                        <div class="client-avatar">AH</div>
                        <div class="client-details">
                            <h5>{{ __('landing.testimonials.client1') }}</h5>
                            <span>{{ __('landing.testimonials.role1') }}</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p>{{ __('landing.testimonials.review2') }}</p>
                    <div class="client-info">
                        <div class="client-avatar" style="background: var(--accent-orange);">MK</div>
                        <div class="client-details">
                            <h5>{{ __('landing.testimonials.client2') }}</h5>
                            <span>{{ __('landing.testimonials.role2') }}</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p>{{ __('landing.testimonials.review3') }}</p>
                    <div class="client-info">
                        <div class="client-avatar" style="background: var(--accent-green);">SL</div>
                        <div class="client-details">
                            <h5>{{ __('landing.testimonials.client3') }}</h5>
                            <span>{{ __('landing.testimonials.role3') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ PARTNERS ════════════════════════ -->
    <section class="section-padding">
        <div class="landing-container">
            <div class="text-center mb-4">
                <small style="color: var(--text-muted); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Integrated With Industry Leaders</small>
            </div>
            <div class="partners-wall">
                <div class="partner-logo">MAERSK</div>
                <div class="partner-logo">DHL LOGISTICS</div>
                <div class="partner-logo">FEDEX EXPRESS</div>
                <div class="partner-logo">DP WORLD</div>
                <div class="partner-logo">COSCO SHIPPING</div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ FAQ SECTION ════════════════════════ -->
    <section class="section-padding bg-navy-alt" id="faq">
        <div class="landing-container">
            <div class="text-center">
                <div class="section-tag"><i class="bi bi-question-circle"></i> {{ __('landing.faq.tag') }}</div>
                <h2 class="section-title">{{ __('landing.faq.title') }} <span class="gradient-accent">{{ __('landing.faq.accent_title') }}</span></h2>
                <p class="section-subtitle">{{ __('landing.faq.subtitle') }}</p>
            </div>

            <div class="faq-wrapper">
                <div class="faq-item active">
                    <div class="faq-header">
                        <span>{{ __('landing.faq.q1') }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-body">
                        {{ __('landing.faq.a1') }}
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-header">
                        <span>{{ __('landing.faq.q2') }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-body">
                        {{ __('landing.faq.a2') }}
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-header">
                        <span>{{ __('landing.faq.q3') }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-body">
                        {{ __('landing.faq.a3') }}
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-header">
                        <span>{{ __('landing.faq.q4') }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-body">
                        {{ __('landing.faq.a4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ════════════════════════ FOOTER ════════════════════════ -->
    <footer class="footer-landing">
        <div class="landing-container">
            <div class="footer-grid">
                <!-- Brand Info -->
                <div class="footer-brand">
                    <a href="{{ url('/' . $langCode . '/welcome') }}" class="brand-logo">
                        @if(!empty($companyLogo))
                            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="max-height: 44px; max-width: 160px; object-fit: contain;">
                        @else
                            <div class="brand-logo-icon">
                                <i class="las la-shipping-fast"></i>
                            </div>
                        @endif
                        <span>{{ $companyName }}</span>
                    </a>
                    <p>
                        {{ $companyDesc }}
                    </p>
                    <div class="social-links">
                        @if(!empty($companyWebsite))
                            <a href="{{ $companyWebsite }}" target="_blank" class="social-icon" title="Website"><i class="bi bi-globe"></i></a>
                        @endif
                        @if(!empty($companyEmail))
                            <a href="mailto:{{ $companyEmail }}" class="social-icon" title="Email"><i class="bi bi-envelope"></i></a>
                        @endif
                        @if(!empty($companyPhone))
                            <a href="tel:{{ $companyPhone }}" class="social-icon" title="Phone"><i class="bi bi-telephone"></i></a>
                        @endif
                        @if(!empty($companyMapUrl))
                            <a href="{{ $companyMapUrl }}" target="_blank" class="social-icon" title="Location"><i class="bi bi-geo-alt"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Column 1 -->
                <div class="footer-col">
                    <h5>{{ __('landing.footer.services') }}</h5>
                    <ul class="footer-links">
                        <li><a href="#services">{{ __('landing.services.air_title') }}</a></li>
                        <li><a href="#services">{{ __('landing.services.ocean_title') }}</a></li>
                        <li><a href="#services">{{ __('landing.services.road_title') }}</a></li>
                        <li><a href="#services">{{ __('landing.services.warehousing_title') }}</a></li>
                        <li><a href="#services">{{ __('landing.services.customs_title') }}</a></li>
                    </ul>
                </div>

                <!-- Column 2 -->
                <div class="footer-col">
                    <h5>{{ __('landing.footer.containers') }}</h5>
                    <ul class="footer-links">
                        <li><a href="#containers">20ft Standard</a></li>
                        <li><a href="#containers">40ft Standard</a></li>
                        <li><a href="#containers">40ft High Cube</a></li>
                        <li><a href="#containers">Reefer Container</a></li>
                        <li><a href="#containers">Open Top & Flat Rack</a></li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div class="footer-col">
                    <h5>{{ __('landing.footer.links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="#calculator">{{ __('landing.nav.calculator') }}</a></li>
                        <li><a href="#tracking">{{ __('landing.nav.tracking') }}</a></li>
                        <li><a href="{{ url('/' . $langCode . '/users/login') }}">{{ __('landing.nav.portal') }}</a></li>
                        <li><a href="#features">{{ __('landing.nav.features') }}</a></li>
                        <li><a href="#faq">{{ __('landing.nav.faq') }}</a></li>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div class="footer-col">
                    <h5>{{ __('landing.footer.contact') }}</h5>
                    <ul class="footer-links">
                        @if(!empty($companyPhone))
                            <li><i class="bi bi-telephone me-1"></i> <a href="tel:{{ $companyPhone }}">{{ $companyPhone }}</a></li>
                        @endif
                        @if(!empty($companyEmail))
                            <li><i class="bi bi-envelope me-1"></i> <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></li>
                        @endif
                        @if(!empty($companyMapUrl))
                            <li><i class="bi bi-geo-alt me-1"></i> <a href="{{ $companyMapUrl }}" target="_blank">Google Maps Location</a></li>
                        @endif
                        @if(!empty($companyWebsite))
                            <li><i class="bi bi-globe me-1"></i> <a href="{{ $companyWebsite }}" target="_blank">{{ parse_url($companyWebsite, PHP_URL_HOST) ?? $companyWebsite }}</a></li>
                        @endif
                        <li><i class="bi bi-clock me-1"></i> 24/7 Dispatch Support</li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div>
                    © {{ date('Y') }} {{ $companyName }}. {{ __('landing.footer.rights') }}
                </div>
                <div style="display: flex; gap: 24px;">
                    <a href="#">{{ __('landing.footer.privacy') }}</a>
                    <a href="#">{{ __('landing.footer.terms') }}</a>
                    <a href="#">{{ __('landing.footer.security') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Script -->
    <script src="{{ asset('js/landing.js') }}"></script>
</body>

</html>
