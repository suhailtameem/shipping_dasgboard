@php
    $currentLang = app()->getLocale();
    $isAr = $currentLang == 'ar' || request()->get('lang') == 'Ar' || request()->route('lang') == 'Ar';
    $langCode = $isAr ? 'Ar' : 'En';
@endphp

<div class="calc-card">
    <div class="calc-form-grid">
        <!-- Transport Type -->
        <div class="form-group-calc">
            <label><i class="bi bi-truck me-1"></i> {{ __('landing.calculator.transport_type') }}</label>
            <select class="input-calc" id="calcTransport">
                <option value="1">{{ __('landing.calculator.air_express') }}</option>
                <option value="2">{{ __('landing.calculator.ocean_container') }}</option>
                <option value="3">{{ __('landing.calculator.road_truck') }}</option>
            </select>
        </div>

        <!-- Origin Country -->
        <div class="form-group-calc">
            <label><i class="bi bi-geo-alt me-1"></i> {{ __('landing.calculator.origin') }}</label>
            <select class="input-calc" id="calcOrigin">
                <!-- Dynamically populated by JS based on transport type -->
            </select>
        </div>

        <!-- Destination Country -->
        <div class="form-group-calc">
            <label><i class="bi bi-geo-fill me-1"></i> {{ __('landing.calculator.destination') }}</label>
            <select class="input-calc" id="calcDest">
                <!-- Dynamically populated by JS based on transport type -->
            </select>
        </div>

        <!-- Cargo Category (from packageType sysList) -->
        <div class="form-group-calc">
            <label><i class="bi bi-box me-1"></i> {{ __('landing.calculator.category') }}</label>
            <select class="input-calc" id="calcCategory">
                @if($cargoCategories && count($cargoCategories) > 0)
                    @foreach($cargoCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $isAr ? ($cat->ar ?? $cat->en) : ($cat->en ?? $cat->ar) }}</option>
                    @endforeach
                @else
                    <option value="general">{{ __('landing.calculator.cat_general') }}</option>
                    <option value="perishable">{{ __('landing.calculator.cat_perishable') }}</option>
                    <option value="hazardous">{{ __('landing.calculator.cat_hazardous') }}</option>
                    <option value="oversized">{{ __('landing.calculator.cat_oversized') }}</option>
                @endif
            </select>
        </div>

        <!-- Cargo Weight -->
        <div class="form-group-calc">
            <label><i class="bi bi-speedometer2 me-1"></i> {{ __('landing.calculator.weight') }}</label>
            <input type="number" class="input-calc" id="calcWeight" placeholder="e.g. 250" value="250" min="1">
        </div>

        <!-- Currency Selector -->
        <div class="form-group-calc">
            <label><i class="bi bi-currency-exchange me-1"></i> Currency / العملة</label>
            <select class="input-calc" id="calcCurrency">
                @if($currencies && count($currencies) > 0)
                    @foreach($currencies as $curr)
                        <option value="{{ $curr->id }}" {{ strtoupper($curr->currency) == 'USD' ? 'selected' : '' }}>
                            {{ $curr->currency }} - {{ $curr->name }} ({{ $curr->symbol ?? $curr->currency }})
                        </option>
                    @endforeach
                @else
                    <option value="1">USD - US Dollar ($)</option>
                @endif
            </select>
        </div>
    </div>

    <div style="text-align: center;">
        <button class="btn-prime btn-orange" id="calcShippingBtn" style="padding: 16px 48px; font-size: 18px;">
            <i class="bi bi-calculator"></i> {{ __('landing.calculator.btn') }}
        </button>
    </div>

    <!-- Instant Estimate Result Display -->
    <div class="calc-result-box">
        <div class="result-text">
            <h5>{{ __('landing.calculator.est_title') }}</h5>
            <h2 id="calcEstimatedCost">$0.00</h2>
            <small id="calcBreakdownText" style="color: var(--text-muted);">{{ __('landing.calculator.breakdown_note') }}</small>
        </div>
        <div>
            <a href="{{ url('/' . $langCode . '/users/login') }}" class="btn-prime" style="padding: 12px 24px; font-size: 14px;">
                {{ __('landing.calculator.book_btn') }} <i class="bi {{ $isAr ? 'bi-arrow-left' : 'bi-arrow-right' }}"></i>
            </a>
        </div>
    </div>
</div>

<script>
    window.shippingCalcData = {
        airDests: @json($airDests),
        seaDests: @json($seaDests),
        landDests: @json($landDests),
        isAr: {{ $isAr ? 'true' : 'false' }},
        calcUrl: "{{ url('/calculate-shipping-cost') }}"
    };
</script>