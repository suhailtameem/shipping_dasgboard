@php
    $currentLocale = app()->getLocale();
    $isArabic = strtolower($currentLocale) == 'ar';
@endphp

<div class="tracking-map-wrapper" id="trackingMapWrapper">
    <!-- Search Input Bar -->
    <form action="{{ url()->current() }}#tracking" method="GET" class="tracking-search-bar" id="trackingForm">
        <input type="text" class="tracking-input" name="TNO" id="inputTrackNumber" value="{{ $searchBar }}" placeholder="{{ __('landing.tracking.placeholder') }}">
        <button type="submit" class="btn-prime" id="btnTrackSearch">
            <i class="bi bi-search"></i> {{ __('landing.tracking.btn') }}
        </button>
    </form>

    <!-- Status Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        @if ($result == 0)
            <span id="trackStatusText" style="font-size: 18px; font-weight: 700; color: var(--accent-green);">
                {{ __('landing.tracking.status_active') }}
            </span>
        @elseif ($result == -1)
            <span id="trackStatusText" style="font-size: 18px; font-weight: 700; color: #dc3545;">
                <i class="bi bi-question-diamond-fill me-1"></i>
                {{ $isArabic ? 'عذراً، لا يوجد شحنة مطابقة لهذا الرقم' : 'Sorry, there is no match for this tracking number' }}
            </span>
        @elseif ($result == -2)
            <span id="trackStatusText" style="font-size: 18px; font-weight: 700; color: #ff9500;">
                <i class="bi bi-clock-history me-1"></i>
                {{ $isArabic ? 'لم يتم شحن هذه الطلبية بعد' : 'The shipment has not been shipped yet' }}
            </span>
        @elseif ($result == 1)
            <span id="trackStatusText" style="font-size: 18px; font-weight: 700; color: var(--accent-green);">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ $isArabic ? 'تفاصيل تتبع الشحنة رقم' : 'Tracking Details for Shipment' }} #{{ $searchBar }}
                ({{ $shippmentComplateProgress }}% {{ $isArabic ? 'مكتمل' : 'Completed' }})
            </span>
        @endif
    </div>

    <!-- Stepper Timeline -->
    @if ($result == 1 && count($shipmentMoves) > 0)
        <div class="tracking-stepper-full mb-4">
            <div class="tracking-progress-fill" id="trackingProgressFill" style="width: {{ $shippmentComplateProgress }}%;"></div>

            @foreach ($shipmentMoves as $item)
                @php
                    $isDone = ($item->move == '1');
                @endphp
                <div class="stepper-node {{ $isDone ? 'completed' : '' }}">
                    <div class="node-dot">
                        @if ($isDone)
                            <i class="bi bi-check"></i>
                        @else
                            <i class="bi bi-circle"></i>
                        @endif
                    </div>
                    <div class="node-label">
                        <div style="font-weight: 700;">{{ $item->details }}</div>
                        @if (!empty($item->location))
                            <div style="font-size: 11px; opacity: 0.8;">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $item->location }}
                            </div>
                        @endif
                        @if (!empty($item->step_date))
                            <div style="font-size: 10px; opacity: 0.65;">
                                <i class="bi bi-calendar2-minus-fill me-1"></i>{{ $item->step_date }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif ($result == 0)
        <!-- Default Mock Stepper Timeline -->
        <div class="tracking-stepper-full" dir="ltr">
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
    @endif

    <!-- Container / Route Summary Card -->
    @if ($result == 1 && !empty($containerInformation) && count($containerInformation) > 0)
        @php
            $info = is_array($containerInformation) ? ($containerInformation[0] ?? null) : $containerInformation->first();
            $from = $info ? ($isArabic ? ($info->from_ar ?? $info->from_en) : ($info->from_en ?? $info->from_ar)) : null;
            $to = $info ? ($isArabic ? ($info->to_ar ?? $info->to_en) : ($info->to_en ?? $info->to_ar)) : null;
            $containerNum = $info->container ?? null;
            $createdDate = $info->date ?? null;
        @endphp
        <div style="background: rgba(0, 113, 227, 0.06); border: 1px solid rgba(0, 113, 227, 0.18); border-radius: var(--radius-md, 12px); padding: 18px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            @if ($from || $to)
                <div>
                    <small style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">{{ $isArabic ? 'المسار' : 'Route' }}</small>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-main);">
                        {{ $from ?? 'Origin' }} <i class="bi {{ $isArabic ? 'bi-arrow-left' : 'bi-arrow-right' }} text-primary"></i> {{ $to ?? 'Destination' }}
                    </div>
                </div>
            @endif
            @if ($containerNum)
                <div>
                    <small style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">{{ __('landing.hero.container_id') }}</small>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-main);">{{ $containerNum }}</div>
                </div>
            @endif
            @if ($createdDate)
                <div>
                    <small style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">{{ $isArabic ? 'تاريخ الشحن' : 'Shipment Date' }}</small>
                    <div style="font-size: 15px; font-weight: 700; color: var(--accent-orange);">{{ $createdDate }}</div>
                </div>
            @endif
        </div>
    @endif

    <!-- Map Canvas Representation -->
    <div class="map-canvas-container">
        <svg width="100%" height="100%" viewBox="0 0 1000 320" preserveAspectRatio="none" style="position: absolute; inset: 0;">
            <!-- SVG World Map Graphic Grid Lines -->
            <path d="M 100 100 Q 300 20 600 120 T 900 80" stroke="rgba(0, 113, 227, 0.4)" stroke-width="3" fill="none" stroke-dasharray="6,6"/>
            <circle cx="100" cy="100" r="8" fill="#0071E3"/>
            <circle cx="600" cy="120" r="10" fill="#FF9500" style="animation: pulse 1.5s infinite;"/>
            <circle cx="900" cy="80" r="8" fill="#34C759"/>

            <text x="90" y="130" fill="var(--text-main)" font-size="13" font-weight="700">{{ __('landing.tracking.origin_loc') }}</text>
            <text x="570" y="155" fill="#FF9500" font-size="14" font-weight="800">{{ __('landing.tracking.current_loc') }}</text>
            <text x="860" y="110" fill="var(--text-main)" font-size="13" font-weight="700">{{ __('landing.tracking.dest_loc') }}</text>
        </svg>
    </div>
</div>