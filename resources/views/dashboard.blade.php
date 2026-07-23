@php
use App\Http\Controllers\shipmentsController;
use App\Models\shMovements;
use App\Models\ShippingRequest;
use App\Models\currencies;
$lang = request()->lang;
$dir = $lang == "Ar"? "rtl" : "ltr";

$allCurrencies = currencies::all();


// Today's movements
$today = \Carbon\Carbon::today()->format('Y-m-d');
$todayMovements = shMovements::with('shipment')
    ->where('step_date', $today)
    ->orderBy('created_at', 'desc')
    ->get();

// Shipping Request status counts per period
$now   = \Carbon\Carbon::now();
$chartPeriods = [
    'all'        => [\Carbon\Carbon::createFromDate(2000, 1, 1)->startOfDay(), $now->copy()->endOfDay()],
    'today'      => [$now->copy()->startOfDay(),     $now->copy()->endOfDay()],
    'week'       => [$now->copy()->startOfWeek(),    $now->copy()->endOfWeek()],
    'month'      => [$now->copy()->startOfMonth(),   $now->copy()->endOfMonth()],
    'year'       => [$now->copy()->startOfYear(),    $now->copy()->endOfYear()],
];

// Status labels: 1=Waiting 2=Accepted 3=Rejected 4=Processing (or postponed)
$statusConfig = [
    '1' => ['label_en' => 'Waiting',    'label_ar' => 'في الانتظار', 'color' => '#0a84ff'],
    '2' => ['label_en' => 'Accepted',   'label_ar' => 'مقبول',       'color' => '#30d158'],
    '3' => ['label_en' => 'Rejected',   'label_ar' => 'مرفوض',       'color' => '#ff453a'],
    '4' => ['label_en' => 'Processing', 'label_ar' => 'قيد التنفيذ', 'color' => '#ffd60a'],
];

$chartData = [];
foreach ($chartPeriods as $key => [$from, $to]) {
    $counts = ShippingRequest::whereBetween('created_at', [$from, $to])
        ->selectRaw('req_status, count(*) as count')
        ->groupBy('req_status')
        ->pluck('count', 'req_status')
        ->toArray();
    $chartData[$key] = [];
    foreach ($statusConfig as $status => $cfg) {
        $chartData[$key][] = $counts[$status] ?? 0;
    }
}

$chartLabels  = array_values(array_map(fn($s) => $lang === 'Ar' ? $s['label_ar'] : $s['label_en'], $statusConfig));
$chartColors  = array_values(array_column($statusConfig, 'color'));

if (!function_exists('getShippingTypeDetails')) {
    function getShippingTypeDetails($sh_type) {
        switch ($sh_type) {
            case '1': return ['name' => __('lang.AirFreight'), 'icon' => 'bi bi-send-fill',      'accent' => '#0a84ff'];
            case '2': return ['name' => __('lang.SeaFreight'), 'icon' => 'las la-ship',           'accent' => '#FF7224'];
            case '3': return ['name' => __('lang.Land'),       'icon' => 'las la-shipping-fast', 'accent' => '#ffd60a'];
            default:  return ['name' => 'Unknown',             'icon' => 'bi bi-box-seam',        'accent' => '#6c757d'];
        }
    }
}
@endphp
<!DOCTYPE html>
<html lang="{{$lang}}">

<head>
    @include('links')
    <title>{{__('lang.dashTapTitle')}}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <style>
        /* ── iOS Movements Widget ── */
        .ios-widget {
            background: #292d30;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,.28);
        }
        .ios-widget-header {
            padding: 16px 18px 12px;
            background: linear-gradient(135deg,#1c1c1e 0%,#2c2c2e 100%);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .ios-widget-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
            margin: 0 0 2px;
        }
        .ios-widget-subtitle {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        .ios-widget-body {
            padding: 4px 0;
            max-height: 440px;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .ios-widget-body::-webkit-scrollbar { display: none; }

        .ios-move-item {
            display: flex;
            gap: 12px;
            padding: 12px 18px;
            position: relative;
            transition: background .18s;
        }
        .ios-move-item:hover { background: rgba(255,255,255,.04); }
        .ios-move-item:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50px;
            right: 18px;
            height: 1px;
            background: rgba(255,255,255,.06);
        }
        .ios-move-dot-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            flex-shrink: 0;
        }
        .ios-move-dot {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .ios-move-info { flex: 1; min-width: 0; }
        .ios-move-type {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .ios-move-container {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ios-move-detail {
            font-size: 12px;
            color: rgba(255,255,255,.5);
            margin-top: 2px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ios-move-time {
            font-size: 11px;
            color: rgba(255,255,255,.35);
            white-space: nowrap;
            padding-top: 3px;
        }
        .ios-move-location {
            font-size: 10px;
            color: rgba(255,255,255,.35);
            margin-top: 3px;
        }
        .ios-empty {
            padding: 36px 18px;
            text-align: center;
            color: rgba(255,255,255,.3);
        }
        .ios-empty i { font-size: 36px; display: block; margin-bottom: 8px; }
        .ios-empty small { font-size: 13px; }

        /* ── Request Status Chart Card ── */
        .chart-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            overflow: hidden;
            margin-top: 20px;
        }
        .chart-card-header {
            padding: 18px 24px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 1px solid #f1f1f1;
        }
        .chart-card-title {
            font-size: 17px;
            font-weight: 700;
            color: #1c1c1e;
            margin: 0;
        }
        .chart-period-tabs {
            display: flex;
            gap: 4px;
            background: #f2f2f7;
            border-radius: 10px;
            padding: 3px;
        }
        .chart-tab {
            border: none;
            background: transparent;
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #8e8e93;
            cursor: pointer;
            transition: all .2s;
        }
        .chart-tab.active {
            background: #fff;
            color: #1c1c1e;
            box-shadow: 0 1px 4px rgba(0,0,0,.12);
        }
        .chart-body {
            padding: 20px 24px 24px;
            display: flex;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
        }
        .chart-canvas-wrap {
            position: relative;
            width: 180px;
            height: 180px;
            flex-shrink: 0;
        }
        .chart-center-label {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .chart-center-total {
            font-size: 28px;
            font-weight: 800;
            color: #1c1c1e;
            line-height: 1;
        }
        .chart-center-sub {
            font-size: 11px;
            color: #8e8e93;
            margin-top: 3px;
        }
        .chart-legend {
            flex: 1;
            min-width: 160px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .legend-label {
            font-size: 13px;
            color: #3c3c43;
            flex: 1;
        }
        .legend-count {
            font-size: 15px;
            font-weight: 700;
            color: #1c1c1e;
        }


        .card{
            background: #292d30 !important;
            color: #fff;
            border-radius: 20px;
        }
        .card .list-title{
            color: #fff !important;
        }
        
        
    </style>
</head>

<body>
    @include('nav-aside')
    @php

        $AirShipments = shipmentsController::getShipments('1');
        $AirCount = count($AirShipments);
    @endphp
    <main class="main-stage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12 mb-3">
                    <div class="card">
                        <div class="row" dir="{{$dir}}">
                            <div class="col-md-4 mb-1">
                                <div class="list-tile">
                                    <div class="tile-leading">
                                        <i class="bi bi-speedometer2"></i>
                                    </div>
                                    <div class="tile-content">
                                        <h4 class="list-title">
                                            {{-- Dashboard --}}
                                            @lang('lang.dashTapTitle')
                                        </h4>
                                        <small class="list-subtitle"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-1">
                                <div class="list-tile">
                                    <div class="tile-leading" id="MoNi-icon" alt="{{$lang}}">
                                        <i class="bi bi-clouds"></i>
                                    </div>
                                    <div class="tile-content"  >
                                        <h4 class="list-title" id="MoNi">Good Morning</h4>
                                        <small class="list-subtitle">
                                            @lang('lang.Mr'). Suhail Tameem
                                        </small>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 mb-1">
                                <div class="list-tile">
                                    <div class="tile-leading">
                                        <i class="bi bi-calendar2-date"></i>
                                    </div>
                                    <div class="tile-content">
                                        <h4 class="list-title">@lang('lang.Todayis')</h4>
                                        <small class="list-subtitle" id="today">
                                            20 Setemper 2021
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-3" dir="{{$dir}}">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/air-freight/') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} blue-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-send-fill"></i>
                                        </div>
                                        <div class="item-indecator">
                                            {{ $AirCount }}/Cargo
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.AirFreight')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/sea-freight') }}" class="links">
                                <div class="menu-item  menu-item-{{$lang}} orange-item">
                                    <div class="ihead">
                                        <div class="item-icon" style="font-size: 32px">
                                            <i class="las la-ship"></i>
                                        </div>
                                        <div class="item-indecator">
                                            {{ $AirCount }}/Container
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.SeaFreight')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/land-transport/') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} yellow-item">
                                    <div class="ihead">
                                        <div class="item-icon" style="font-size: 32px">
                                            <i class="las la-shipping-fast"></i>
                                        </div>
                                        <div class="item-indecator">
                                            {{ $AirCount }}/Shipment
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.Land')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/rates/') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} bink-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-currency-exchange"></i>
                                        </div>
                                        <div class="item-indecator">
                                            Currancies
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.Currencies')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/users') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} black-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <div class="item-indecator">
                                            0/User
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.SystemUsers')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/request-list') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} green-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-inbox-fill"></i>
                                        </div>
                                        <div class="item-indecator">
                                            0/Request
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.ShippingRequest')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/Mobile') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} darkBlue-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-phone-fill"></i>
                                        </div>
                                        <div class="item-indecator">
                                            Settings
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.mobileSettings')
                                    </h4>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <a href="{{ url(request()->lang . '/sys-lists') }}" class="links">
                                <div class="menu-item menu-item-{{$lang}} red-item">
                                    <div class="ihead">
                                        <div class="item-icon">
                                            <i class="bi bi-list-stars"></i>
                                        </div>
                                        <div class="item-indecator">
                                            Lists
                                        </div>
                                    </div>
                                    <h4 class="item-title">
                                        @lang('lang.SystemLists')
                                    </h4>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            {{-- ═══ Shipment Request Status Chart ═══ --}}
                            <div class="chart-card" dir="{{$dir}}">
                                <div class="chart-card-header">
                                    <h5 class="chart-card-title">
                                        <i class="bi bi-pie-chart-fill me-2" style="color:#0a84ff;"></i>
                                        @lang('lang.ShippingRequest') — @lang('lang.Status')
                                    </h5>
                                    <div class="chart-period-tabs" role="group">
                                        <button class="chart-tab active" data-period="all">@lang('lang.AllTime')</button>
                                        <button class="chart-tab" data-period="today">@lang('lang.Today')</button>
                                        <button class="chart-tab" data-period="week">@lang('lang.ThisWeek')</button>
                                        <button class="chart-tab" data-period="month">@lang('lang.LastMonth')</button>
                                        <button class="chart-tab" data-period="year">@lang('lang.ThisYear')</button>
                                    </div>
                                </div>
                                <div class="chart-body">
                                    <div class="chart-canvas-wrap">
                                        <canvas id="reqStatusChart" width="180" height="180"></canvas>
                                        <div class="chart-center-label">
                                            <span class="chart-center-total" id="chartTotal">0</span>
                                            <span class="chart-center-sub">@lang('lang.Total')</span>
                                        </div>
                                    </div>
                                    <div class="chart-legend" id="chartLegend"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- ═══ iOS Movements Widget ═══ --}}
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3" dir="{{$dir}}">
                    <div class="ios-widget mt-0">
                        <div class="ios-widget-header">
                            <p class="ios-widget-title">@lang('lang.TodayActions')</p>
                            <p class="ios-widget-subtitle">
                                {{ $todayMovements->count() }}
                                <span style="font-size:13px;font-weight:500;color:rgba(255,255,255,.4);">{{ $todayMovements->count() == 1 ? 'move' : 'moves' }}</span>
                            </p>
                        </div>
                        <div class="ios-widget-body">
                            @if($todayMovements->isEmpty())
                                <div class="ios-empty">
                                    <i class="bi bi-calendar-x"></i>
                                    <small>@lang('lang.NoTodayMovements')</small>
                                </div>
                            @else
                                @foreach($todayMovements as $move)
                                    @php
                                        $sd = getShippingTypeDetails($move->shipment->sh_type ?? '0');
                                    @endphp
                                    <div class="ios-move-item">
                                        <div class="ios-move-dot-wrap">
                                            <div class="ios-move-dot" style="background-color:{{ $sd['accent'] }}22; color:{{ $sd['accent'] }};">
                                                <i class="{{ $sd['icon'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="ios-move-info">
                                            <div class="ios-move-type" style="color:{{ $sd['accent'] }}">{{ $sd['name'] }}</div>
                                            <div class="ios-move-container">{{ $move->shipment->container ?? '—' }}</div>
                                            <div class="ios-move-detail">{{ $move->details }}</div>
                                            @if(!empty($move->location))
                                                <div class="ios-move-location">
                                                    <i class="bi bi-geo-alt-fill" style="color:#ff453a"></i> {{ $move->location }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ios-move-time">{{ date('H:i', strtotime($move->created_at)) }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- ═══ iOS Currencies Widget ═══ --}}
                    <div class="ios-widget" style="margin-top: 20px;">
                        <div class="ios-widget-header">
                            <p class="ios-widget-title">@lang('lang.Currencies')</p>
                            <p class="ios-widget-subtitle">
                                {{ $allCurrencies->count() }}
                                <span style="font-size:13px;font-weight:500;color:rgba(255,255,255,.4);">@lang('lang.Rates')</span>
                            </p>
                        </div>
                        <div class="ios-widget-body" style="max-height: 250px;">
                            @if($allCurrencies->isEmpty())
                                <div class="ios-empty">
                                    <i class="bi bi-cash-coin"></i>
                                    <small>No Currencies found</small>
                                </div>
                            @else
                                @foreach($allCurrencies as $curr)
                                    <div class="ios-move-item">
                                        <div class="ios-move-dot-wrap">
                                            <div class="ios-move-dot" style="background-color: rgba(48, 209, 88, 0.15); color: #30d158;">
                                                <i class="bi bi-cash-stack"></i>
                                            </div>
                                        </div>
                                        <div class="ios-move-info">
                                            <div class="ios-move-type" style="color: #30d158;">{{ $lang == 'Ar' ? $curr->currency_ar : $curr->currency }}</div>
                                            <div class="ios-move-container">{{ $curr->currency }}</div>
                                            <div class="ios-move-detail">@lang('lang.UsdRate'): 1 USD = {{ number_format($curr->usdRate, 4) }} {{ $curr->currency }}</div>
                                        </div>
                                        <div class="ios-move-time" style="font-size: 14px; font-weight: bold; color: #fff; padding-top: 6px;">
                                            {{ round($curr->usdRate, 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
           

        </div>
    </main>
    {{-- ═══ Chart Init Script ═══ --}}
    <script>
    (function() {
        const chartDataAll = @json($chartData);
        const chartLabels  = @json($chartLabels);
        const chartColors  = @json($chartColors);

        let currentChart = null;

        function renderChart(period) {
            const data  = chartDataAll[period] || [0,0,0,0];
            const total = data.reduce((a, b) => a + b, 0);

            document.getElementById('chartTotal').textContent = total;

            // Legend
            const legend = document.getElementById('chartLegend');
            legend.innerHTML = '';
            chartLabels.forEach((label, i) => {
                legend.innerHTML += `
                    <div class="legend-item">
                        <span class="legend-dot" style="background:${chartColors[i]}"></span>
                        <span class="legend-label">${label}</span>
                        <span class="legend-count">${data[i]}</span>
                    </div>`;
            });

            // Chart
            const canvas = document.getElementById('reqStatusChart');
            const ctx    = canvas.getContext('2d');
            if (currentChart) currentChart.destroy();

            const isEmpty = total === 0;
            currentChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data:            isEmpty ? [1]          : data,
                        backgroundColor: isEmpty ? ['#e5e5ea'] : chartColors,
                        borderWidth:     2,
                        borderColor:     '#fff',
                        hoverOffset:     isEmpty ? 0 : 6,
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '70%',
                    animation: { animateRotate: true, duration: 600 },
                    plugins: {
                        legend:  { display: false },
                        tooltip: { enabled: !isEmpty }
                    }
                }
            });
        }

        document.querySelectorAll('.chart-tab').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.chart-tab').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                renderChart(this.dataset.period);
            });
        });

        renderChart('all');
    })();
    </script>
</body>
@include('scripts')

</html>
