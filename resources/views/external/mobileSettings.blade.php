@php
use App\Http\Controllers\customerController as Customers;
use App\Http\Controllers\listsControllrt as Lists;

$lang = request()->lang;
$dir = $lang == "Ar"? "rtl" : "ltr";
$CenterArText = $lang == "Ar"? "text-center" : " ";

$customers = Customers::getCustomers();
$notfList = Lists::getNotifList();
$sysNotf = Lists::getFeaturesByNo('1'); //sysNotf= 1

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    @include('links')
    <title>
        @lang('lang.mobileSettings')
    </title>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>

<body>
    @include('nav-aside')
    <main class="main-stage">
        <div class="container">
            <div class="row border-bottom pb-1 mb-3" dir="{{$dir}}">
                <div class="col">
                    <h4 class="main-title">
                        <i class="bi bi-phone-fill"></i>
                        @lang('lang.mobileSettings')
                    </h4>
                </div>
                <div class="col"></div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="d-flex align-items-start">

                            <div class="col-md-8 col-sm-12 mb-3">
                                <section class="content-area">

                                    {{-- ============= collapse No 0 ============= --}}
                                    <div class="bg-light border-rounded p-3">
                                        @include('external.AppSettings')
                                    </div>

                                    {{-- ============= collapse No 1 ============= --}}
                                    <div class="p-3 border border-rounded w-100 bg-light rounded bg-gradiant" >
                                        <form action="{{ url('/sendNotifi') }}" method="POST">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3" dir="{{$dir}}">
                                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                        <h5 class="mb-3 main-title">
                                                            <i class="bi bi-people-fill mx-2"></i>
                                                            @lang('lang.CustomersList')
                                                        </h5>

                                                        <button class="btn btn-sm btn btn-primary select-all">
                                                            <i class="bi bi-person-check-fill"></i>
                                                        </button>
                                                    </div>

                                                    @foreach ($customers as $cus)
                                                        <div
                                                            class=" d-flex align-items-center p-2 mb-2 shadow-sm customerListItem">
                                                            <div class="d-flex">
                                                                <input name="tokensNo[]" class="form-check-input "
                                                                    type="checkbox" value="{{ $cus->id }}"
                                                                    id="token_{{ $cus->id }}">
                                                                <input type="hidden" name="token_{{ $cus->id }}"
                                                                    value="{{ $cus->token }}">
                                                            </div>
                                                            <label for="token_{{ $cus->id }}" class="w-100 cursor">
                                                                <div class="border-start border-end mx-2 px-2">

                                                                    <h6 class="form-check-label mb-0">
                                                                        {{ $cus->first }} {{ $cus->last }}
                                                                    </h6>
                                                                    <small class="">
                                                                        {{ $cus->full }}
                                                                    </small>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3 border-start" dir="{{$dir}}">
                                                    <h5 class="border-b pb-2 mb-3 main-title">
                                                        <i class="bi bi-megaphone-fill mx-2"></i>
                                                        @lang('lang.SendNotification')
                                                    </h5>
                                                    <div class="mb-3">
                                                        <label for="notif-title" class="form-label">
                                                            @lang('lang.NotifTitle')
                                                        </label>
                                                        <input type="text" class="form-control" name="notfTitle"
                                                            id="notif-title" placeholder="@lang('lang.NotifTitleHolder')"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notif-content" class="form-label">
                                                            @lang('lang.NotifContent')
                                                        </label>
                                                        <textarea class="form-control" id="notif-content" name="notifContent" placeholder="@lang('lang.NotifContentHolder')" required></textarea>
                                                    </div>
                                                    @csrf
                                                    <button class="btn btn-success mt-3 w-100">
                                                        @lang('lang.SendNotif')
                                                        <i class="bi bi-send-fill mx-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- ============= collapse No 2 ============= --}}
                                    <div class="p-3 border border-rounded w-100 bg-light rounded bg-gradiant">
                                        <div class="row" >
                                            <div class="col" dir="{{$dir}}">
                                                @foreach ($sysNotf as $item)
                                                    <form action="{{ url('/updateFeature') }}" method="POST">
                                                        @php
                                                            $value = $item->value == 'on' ? 'checked' : 'alt';
                                                            $class = $item->value == 'on' ? 'success' : 'danger';
                                                        @endphp
                                                        @csrf
                                                        <input type="hidden" name="fid"
                                                            value="{{ $item->id }}">
                                                        <label class="form-check-label w-100"
                                                            for="fr_{{ $item->id }}">
                                                            <div
                                                                class="alert alert-{{ $class }} d-flex justify-content-between">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" name="value"
                                                                        type="checkbox" id="fr_{{ $item->id }}"
                                                                        {{ $value }}>
                                                                    {{ $item->name }}
                                                                </div>

                                                                <button class="btn btn-sm btn-light">
                                                                    @lang('lang.update')
                                                                </button>
                                                            </div>
                                                        </label>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-2" dir="{{$dir}}">
                                                <form action="{{ url('/updateNotifications') }}" method="POST">
                                                    @foreach ($notfList as $item)
                                                        <div class="p-3 messagesContainer shadow-sm mb-3">
                                                            <h6 class="main-title border-bottom pb-2 text-center">
                                                                {{ $item->desc }}

                                                                @csrf
                                                                <input type="hidden" name="ids[]"
                                                                    value="{{ $item->id }}">
                                                            </h6>
                                                            <span class="sub-title">
                                                                @lang('lang.NotifTitle')
                                                            </span>
                                                            <div class="input-group mb-3" dir="ltr">
                                                                <span class="input-group-text">
                                                                    <i class="bi bi-chat-left-dots-fill"></i>
                                                                </span>
                                                                <input
                                                                    type="text"
                                                                    name="title_en[]"
                                                                    placeholder="@lang('lang.NotifTitleHolderEn')"
                                                                    class="form-control"
                                                                    value="{{ $item->title_en }}" required>
                                                                <input
                                                                    type="text" name="title_ar[]"
                                                                    placeholder="@lang('lang.NotifTitleHolderAr')"
                                                                    dir="rtl"
                                                                    class="form-control"
                                                                    value="{{ $item->title_ar }}" required>
                                                            </div>

                                                            <span class="sub-title">
                                                                @lang('lang.NotifContent')
                                                            </span>
                                                            <div class="input-group" dir="ltr">
                                                                <span class="input-group-text">
                                                                    <i class="bi bi-chat-left-text-fill"></i>
                                                                </span>
                                                                <input
                                                                    type="text"
                                                                    name="msg_en[]"
                                                                    placeholder="@lang('lang.NotifContentHolderEn')"
                                                                    class="form-control"
                                                                    value="{{ $item->msg_en }}" required>
                                                                <input
                                                                    type="text"
                                                                    name="msg_ar[]"
                                                                    placeholder="@lang('lang.NotifContentHolderAr')"
                                                                    dir="rtl"
                                                                    class="form-control"
                                                                    value="{{ $item->msg_ar }}" required>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <div class="row">
                                                        <div class="col text-center">
                                                            <button class="btn btn-sm btn-primary mt-4 mb-5 ">
                                                                Update Notification Messages
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>


                                    {{-- ============= collapse No 3 ============= --}}
                                    <div class="bg-light border-rounded p-3">
                                        @include('external.companySettings')
                                    </div>

                                    <div>5</div>
                                </section>

                            </div>

                            <div class="col-md-4 col-sm-12 mb-3 px-2" dir="{{$dir}}">
                                <ul class="list-group">
                                    <li class="list-group-item mobileNav" alt="0">
                                        <i class="bi bi-sliders mx-2"></i>
                                        App Settings
                                    </li>
                                    <li class="list-group-item mobileNav" alt="1">
                                        <i class="bi bi-megaphone-fill mx-2"></i>
                                        @lang('lang.MobileNotifi')
                                    </li>
                                    <li class="list-group-item mobileNav" alt="2">
                                        <i class="bi bi-menu-up mx-2"></i>
                                        @lang('lang.SystemNotifi')
                                    </li>
                                    <li class="list-group-item mobileNav" alt="3">
                                        <i class="bi bi-building mx-2"></i>
                                        @lang('lang.CompanySettings')
                                    </li>
                                    {{-- <li class="list-group-item mobileNav" alt="4">And a fifth one</li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </main>
</body>
@include('scripts')
<script src="{{ asset('js/mobile.js') }}"></script>


</html>
