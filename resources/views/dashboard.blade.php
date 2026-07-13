@php
use App\Http\Controllers\shipmentsController;
$lang = request()->lang;
$dir = $lang == "Ar"? "rtl" : "ltr";

@endphp
<!DOCTYPE html>
<html lang="{{$lang}}">

<head>
    @include('links')
    <title>{{__('lang.dashTapTitle')}}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    @include('nav-aside')
    @php

        $AirShipments = shipmentsController::getShipments('1');
        $AirCount = count($AirShipments);
    @endphp
    <main class="main-stage">
        <div class="container-fluid">
            <div class="col-lg-9 col-md-8 col-sm-12 mb-3">
                <div class="row border-top border-bottom" dir="{{$dir}}">
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
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 mb-3">

            </div>
        </div>
    </main>
</body>
@include('scripts')

</html>
