@php
    $currencies = \App\Models\currencies::all();
    $lang = request()->lang ?? 'en';

    // Check if USD already exists in DB
    $hasUsd = false;
    foreach ($currencies as $curr) {
        if (strtoupper($curr->currency) === 'USD') {
            $hasUsd = true;
            break;
        }
    }

    // Force chevron arrow position to right side since select layout is LTR
    $selectBgStyle = "background: rgba(255,255,255,0.06) url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e\") no-repeat right 12px center/12px; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px 14px; font-weight: 600; outline: none; box-shadow: none;";
@endphp

<!-- Currency Calculator Modal (iOS style) -->
<div class="modal fade" id="currencyCalcModal" tabindex="-1" aria-labelledby="currencyCalcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ios-calc-modal" dir="ltr" style="background: #1c1c1e; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); overflow: hidden; text-align: left !important;">
            <div class="modal-header border-0 pb-0" style="padding: 20px 24px 10px; direction: ltr !important;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="currencyCalcModalLabel" style="font-size: 19px; text-align: left !important;">
                    <i class="bi bi-calculator" style="color: #30d158;"></i>
                    Currency Converter
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-none m-0" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px; opacity: 0.7; margin-left: auto !important; margin-right: 0 !important;"></button>
            </div>
            <div class="modal-body" style="padding: 16px 24px 24px; direction: ltr !important; text-align: left !important;">
                <div class="mb-4">
                    <label class="form-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.45); text-align: left !important; display: block;">
                        Amount
                    </label>
                    <div class="input-group">
                        <input type="number" id="calcAmount" class="form-control text-white" value="1" min="0" step="any" placeholder="0.00" 
                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px 16px; font-size: 18px; font-weight: 600; outline: none; box-shadow: none; text-align: left !important;">
                    </div>
                </div>

                <div class="row align-items-end g-3 mb-4">
                    <div class="col-5">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.45); text-align: left !important; display: block;">
                            From
                        </label>
                        <select id="calcFrom" class="form-select text-white select-ios" style="{{ $selectBgStyle }}">
                            @if(!$hasUsd)
                                <option value="usd" data-rate="1.0" data-code="USD" class="bg-dark" selected>
                                    USD - {{ $lang == 'Ar' ? 'دولار أمريكي' : 'US Dollar' }}
                                </option>
                            @endif
                            @foreach($currencies as $curr)
                                <option value="{{ $curr->id }}" data-rate="{{ $curr->usdRate }}" data-code="{{ $curr->currency }}" class="bg-dark" {{ $hasUsd && $curr->currency == 'USD' ? 'selected' : '' }}>
                                    {{ $curr->currency }} - {{ $lang == 'Ar' ? $curr->currency_ar : $curr->currency }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2 text-center pb-1">
                        <button type="button" id="calcSwap" class="btn d-inline-flex align-items-center justify-content-center" 
                            style="width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #fff; transition: all 0.2s;">
                            <i class="bi bi-arrow-left-right" style="font-size: 16px;"></i>
                        </button>
                    </div>

                    <div class="col-5">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.45); text-align: left !important; display: block;">
                            To
                        </label>
                        <select id="calcTo" class="form-select text-white select-ios" style="{{ $selectBgStyle }}">
                            @if(!$hasUsd)
                                <option value="usd" data-rate="1.0" data-code="USD" class="bg-dark">
                                    USD - {{ $lang == 'Ar' ? 'دولار أمريكي' : 'US Dollar' }}
                                </option>
                            @endif
                            @foreach($currencies as $curr)
                                <option value="{{ $curr->id }}" data-rate="{{ $curr->usdRate }}" data-code="{{ $curr->currency }}" class="bg-dark" {{ ($hasUsd && $curr->currency != 'USD' && $loop->iteration == 2) || (!$hasUsd && $loop->first) ? 'selected' : '' }}>
                                    {{ $curr->currency }} - {{ $lang == 'Ar' ? $curr->currency_ar : $curr->currency }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Result Card -->
                <div class="p-3" dir="ltr" style="background: rgba(48, 209, 88, 0.08); border: 1px solid rgba(48, 209, 88, 0.15); border-radius: 16px; text-align: left !important;">
                    <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(48, 209, 88, 0.75); margin-bottom: 4px; text-align: left !important;">
                        Result
                    </div>
                    <div id="calcResultDisplay" class="fw-bold text-white" style="font-size: 22px; word-break: break-all; text-align: left !important;">
                        0.00
                    </div>
                    <div id="calcRateInfo" style="font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 6px; text-align: left !important;">
                        1 USD = 1.00 USD
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling select-ios options for rtl support if needed */
    .select-ios option {
        background: #1c1c1e !important;
        color: #fff !important;
    }
    #calcSwap:hover {
        background: rgba(255,255,255,0.15) !important;
        transform: scale(1.05);
    }
    #calcSwap:active {
        transform: scale(0.95);
    }
    .ios-calc-modal input::-webkit-outer-spin-button,
    .ios-calc-modal input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .ios-calc-modal input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calcAmount = document.getElementById('calcAmount');
    const calcFrom = document.getElementById('calcFrom');
    const calcTo = document.getElementById('calcTo');
    const calcSwap = document.getElementById('calcSwap');
    const calcResultDisplay = document.getElementById('calcResultDisplay');
    const calcRateInfo = document.getElementById('calcRateInfo');

    function performConversion() {
        const amount = parseFloat(calcAmount.value);
        if (isNaN(amount) || amount < 0) {
            calcResultDisplay.textContent = '0.00';
            return;
        }

        const fromOpt = calcFrom.options[calcFrom.selectedIndex];
        const toOpt = calcTo.options[calcTo.selectedIndex];

        const rateFrom = parseFloat(fromOpt.dataset.rate) || 1.0;
        const rateTo = parseFloat(toOpt.dataset.rate) || 1.0;

        const codeFrom = fromOpt.dataset.code;
        const codeTo = toOpt.dataset.code;

        // Conversion formula: Amount * (RateTo / RateFrom)
        // because rates are units per 1 USD (e.g. 1 USD = 3.75 SAR, so SAR rate is 3.75)
        const result = amount * (rateTo / rateFrom);

        // Standard decimal formatting
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 4
        });

        calcResultDisplay.textContent = `${formatter.format(amount)} ${codeFrom} = ${formatter.format(result)} ${codeTo}`;

        // Show exchange rate reference
        const unitRate = rateTo / rateFrom;
        calcRateInfo.textContent = `1 ${codeFrom} = ${unitRate.toFixed(4)} ${codeTo}`;
    }

    // Bind event listeners
    calcAmount.addEventListener('input', performConversion);
    calcFrom.addEventListener('change', performConversion);
    calcTo.addEventListener('change', performConversion);

    // Swap button functionality
    calcSwap.addEventListener('click', function () {
        const fromVal = calcFrom.value;
        calcFrom.value = calcTo.value;
        calcTo.value = fromVal;
        performConversion();
    });

    // Run once on load
    performConversion();

    // Re-run when modal is displayed to sync inputs
    const modalEl = document.getElementById('currencyCalcModal');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', function () {
            calcAmount.focus();
            performConversion();
        });
    }
});
</script>
