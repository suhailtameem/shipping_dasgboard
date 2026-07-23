<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>@lang('lang.CreateShippingRequest')</title>
    <link rel="stylesheet" href="{{ asset('css/request-details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-request.css') }}">
</head>
<body>
    @include('nav-aside')
    <div class="web-container" dir="{{$dir}}">
        <!-- Header -->
        <div class="ios-header">
            <div class="d-flex align-items-center">
                <a href="{{url('/' . $lang . '/request-list')}}" class="text-primary me-3 f20">
                    <div class="backArrow ms-4 text-dark">
                        @if($dir == "ltr")
                        <i class="bi bi-arrow-left"></i>
                        @else
                        <i class="bi bi-arrow-right"></i>
                        @endif
                    </div>
                </a>
                <div>
                    <h1 class="ios-title">@lang('lang.CreateNewRequest')</h1>
                    <div class="text-muted small">@lang('lang.FillDetailsBelow')</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button id="submitRequestBtn" class="ios-btn btn-sm">
                    <i class="bi bi-check-circle me-1"></i> @lang('lang.Save')
                </button>
            </div>
        </div>

        <form id="createRequestForm" action="{{ url('/newShipment') }}" method="post">
            @csrf
            <!-- Validation Alert -->
            <div id="validationAlert" class="alert alert-danger mb-4" style="display: none; border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 20px;"></i>
                    <span id="validationMsg"></span>
                </div>
            </div>
            <input type="hidden" name="getway" value="1" title="admin">
            <input type="hidden" name="getwayType" value="1" title="web">
            <input type="hidden" name="cuid" value="{{ Session::get('user')->id ?? 1 }}">
            <input type="hidden" name="lang" value="{{ $lang }}" title="">


            <div class="row mb-4    ">
                <div class="col-lg-8">
                    <!-- Step 1: Shipping Type -->
                    <div class="ios-card mb-4 step-card">
                        <div class="ios-group-header bg-soft-primary">
                            <div class="step-number">1</div>
                            <i class="bi bi-truck me-2"></i>
                            @lang('lang.ShippingType') <span class="text-danger ms-1">*</span>
                        </div>
                        <div class="p-4">
                            <div class="row g-3 shipping-types-grid">
                                @foreach($shippingTypes as $type)
                                <div class="col-md-4 col-6">
                                    <label class="shipping-type-card">
                                        <input type="radio" name="shippType" value="{{ $type->value }}" class="shipping-type-radio d-none" data-type="{{ $type->value }}">
                                        <div class="shipping-type-inner">
                                            <div class="shipping-type-icon">
                                                <img src="{{ $type->imgUrl ?? asset('imgs/box_def.jpg') }}" alt="{{ $lang == 'Ar' ? $type->ar : $type->en }}">
                                            </div>
                                            <div class="shipping-type-name">
                                                {{ $lang == 'Ar' ? $type->ar : $type->en }}
                                            </div>
                                            <div class="check-indicator">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Step 2: Destinations -->
                    <div class="ios-card mb-4 step-card">
                        <div class="ios-group-header bg-soft-warning">
                            <div class="step-number">2</div>
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            @lang('lang.Destinations')
                        </div>
                        <div class="p-4 n">
                            <div class="destinations-wrapper d-flex flex-colum">
                                <div class="destination-card">
                                    <div class="dest-label">
                                        <i class="bi bi-arrow-up-circle-fill text-success"></i>
                                        @lang('lang.From')
                                    </div>
                                    <select name="fromCountry" id="fromCountry" class="form-select form-select-lg">
                                        <!-- Populated by JS -->
                                    </select>
                                </div>
                              
                                <div class="destination-card">
                                    <div class="dest-label">
                                        <i class="bi bi-arrow-down-circle-fill text-primary"></i>
                                        @lang('lang.To')
                                    </div>
                                    <select name="toCountry" id="toCountry" class="form-select form-select-lg">
                                        <!-- Populated by JS -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Container Type -->
            <div class="ios-card mb-4 step-card">
                <div class="ios-group-header bg-soft-success">
                    <div class="step-number">3</div>
                    <i class="bi bi-box-seam me-2"></i>
                    @lang('lang.ContainerType') <span class="text-danger ms-1">*</span>
                </div>
                <div class="p-4">
                    <div class="container-types-wrapper">
                        @foreach($containerTypes as $type)
                        <div class="container-type-group mb-4">
                            <label class="container-type-card">
                                <input type="checkbox" name="containerType[]" value="{{ $type->value }}" class="container-type-checkbox d-none" data-id="{{ $type->id }}">
                                <div class="container-type-inner">
                                    <div class="container-type-icon">
                                        <img src="{{ $type->imgUrl ?? asset('imgs/box_def.jpg') }}" alt="{{ $lang == 'Ar' ? $type->ar : $type->en }}">
                                    </div>
                                    <div class="container-type-name">
                                        {{ $lang == 'Ar' ? $type->ar : $type->en }}
                                    </div>
                                    <div class="check-indicator @if($lang == 'Ar') check-indecator-ar @endif">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </div>
                            </label>

                            @if($type->has_sub && $type->subLists->count() > 0)
                            <div class="sub-lists-wrapper mt-4" id="subList-{{ $type->id }}" style="display: none;">
                                <div class="sub-lists-label">@lang('lang.SelectSubTypes')</div>
                                <div class="row g-3 ms-3">
                                    @foreach($type->subLists as $sub)
                                    <div class="col-md-3 col-6">
                                        <label class="sub-list-card">
                                            <input type="checkbox" name="subContainerType[]" value="{{ $sub->id }}" class="sub-list-checkbox d-none" data-parent="{{ $type->id }}">
                                            <div class="sub-list-inner">
                                                <div class="sub-list-icon">
                                                    <img src="{{ $sub->imgUrl ?? asset('imgs/box_def.jpg') }}" alt="{{ $lang == 'Ar' ? $sub->ar : $sub->en }}">
                                                </div>
                                                <div class="sub-list-name">
                                                    {{ $lang == 'Ar' ? $sub->ar : $sub->en }}
                                                </div>
                                                <div class="check-indicator">
                                                    <i class="bi bi-check2-circle"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Step 4: Services -->
            <div class="ios-card mb-4 step-card">
                <div class="ios-group-header bg-soft-secondary">
                    <div class="step-number">4</div>
                    <i class="bi bi-gear-fill me-2"></i>
                    @lang('lang.AdditionalServices')
                </div>
                <div class="p-4">
                    <div class="services-wrapper">
                        @foreach($serviceTypes as $type)
                        <div class="service-type-group mb-4">
                            <label class="service-type-card">
                                <input type="checkbox" name="serviceType[]" value="{{ $type->value }}" class="service-type-checkbox d-none" data-id="{{ $type->id }}">
                                <div class="service-type-inner">
                                    <div class="service-type-icon">
                                        <img src="{{ $type->imgUrl ?? asset('imgs/box_def.jpg') }}" alt="{{ $lang == 'Ar' ? $type->ar : $type->en }}">
                                    </div>
                                    <div class="service-type-name">
                                        {{ $lang == 'Ar' ? $type->ar : $type->en }}
                                    </div>
                                    <div class="check-indicator">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </div>
                            </label>

                            @if($type->has_sub && $type->subLists->count() > 0)
                            <div class="sub-lists-wrapper mt-4" id="serviceSubList-{{ $type->id }}" style="display: none;">
                                <div class="sub-lists-label">@lang('lang.SelectSubServices')</div>
                                <div class="row g-3 ms-3">
                                    @foreach($type->subLists as $sub)
                                    <div class="col-md-3 col-6">
                                        <label class="sub-list-card">
                                            <input type="checkbox" name="subServiceType[]" value="{{ $sub->id }}" class="sub-list-checkbox d-none" data-parent="{{ $type->id }}">
                                            <div class="sub-list-inner">
                                                <div class="sub-list-icon">
                                                    <img src="{{ $sub->imgUrl ?? asset('imgs/box_def.jpg') }}" alt="{{ $lang == 'Ar' ? $sub->ar : $sub->en }}">
                                                </div>
                                                <div class="sub-list-name">
                                                    {{ $lang == 'Ar' ? $sub->ar : $sub->en }}
                                                </div>
                                                <div class="check-indicator">
                                                    <i class="bi bi-check2-circle"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>

        <!-- Fixed Bottom Action Bar -->
        
        <div class="ios-container py-0 mt-4">
            <div class="d-flex justify-content-start align-items-center">
                <button id="submitRequestBtnBottom" class="ios-btn px-5 py-2 btn-lg mb-3">
                    <i class="bi bi-check-circle me-2"></i> @lang('lang.CreateShippingRequest')
                </button>
            </div>
        </div>
        
    </div>

    <!-- Data for JS -->
    <script>
        window.createRequestData = {
            lang: "{{ $lang }}",
            airDests: @json($airDests),
            seaDests: @json($seaDests),
            landDests: @json($landDests),
            translations: {
                selectShippingType: "@lang('lang.ErrSelectShippingType')",
                selectContainerType: "@lang('lang.ErrSelectContainerType')",
                selectSubContainerType: "@lang('lang.ErrSelectSubContainerType')",
                selectSubServiceType: "@lang('lang.ErrSelectSubServiceType')"
            }
        };
    </script>
    @include('scripts')
    <script src="{{ asset('js/create-request.js') }}"></script>
</body>
</html>
