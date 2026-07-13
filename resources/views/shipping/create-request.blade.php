<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>Create Shipping Request</title>
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
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div>
                    <h1 class="ios-title">Create New Request</h1>
                    <div class="text-muted small">Fill in the details below</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button id="submitRequestBtn" class="ios-btn btn-sm">
                    <i class="bi bi-check-circle me-1"></i> Save
                </button>
            </div>
        </div>

        <form id="createRequestForm" action="{{ url('/newShipment') }}" method="post">
            @csrf
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
                            Shipping Type
                        </div>
                        <div class="p-4">
                            <div class="row g-3 shipping-types-grid">
                                @foreach($shippingTypes as $type)
                                <div class="col-md-4 col-6">
                                    <label class="shipping-type-card">
                                        <input type="radio" name="shippType" value="{{ $type->value }}" class="shipping-type-radio d-none" data-type="{{ $type->value }}" @if($loop->first) checked @endif>
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
                            Destinations
                        </div>
                        <div class="p-4 n">
                            <div class="destinations-wrapper d-flex flex-colum">
                                <div class="destination-card">
                                    <div class="dest-label">
                                        <i class="bi bi-arrow-up-circle-fill text-success"></i>
                                        From
                                    </div>
                                    <select name="fromCountry" id="fromCountry" class="form-select form-select-lg">
                                        <!-- Populated by JS -->
                                    </select>
                                </div>
                              
                                <div class="destination-card">
                                    <div class="dest-label">
                                        <i class="bi bi-arrow-down-circle-fill text-primary"></i>
                                        To
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
                    Container Type
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
                                    <div class="check-indicator">
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                </div>
                            </label>

                            @if($type->has_sub && $type->subLists->count() > 0)
                            <div class="sub-lists-wrapper mt-4" id="subList-{{ $type->id }}" style="display: none;">
                                <div class="sub-lists-label">Select Sub-types:</div>
                                <div class="row g-3 ms-3">
                                    @foreach($type->subLists as $sub)
                                    <div class="col-md-3 col-6">
                                        <label class="sub-list-card">
                                            <input type="checkbox" name="subContainerType[]" value="{{ $sub->value }}" class="sub-list-checkbox d-none" data-parent="{{ $type->id }}">
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
                    Additional Services
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
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                </div>
                            </label>

                            @if($type->has_sub && $type->subLists->count() > 0)
                            <div class="sub-lists-wrapper mt-4" id="serviceSubList-{{ $type->id }}" style="display: none;">
                                <div class="sub-lists-label">Select Sub-services:</div>
                                <div class="row g-3 ms-3">
                                    @foreach($type->subLists as $sub)
                                    <div class="col-md-3 col-6">
                                        <label class="sub-list-card">
                                            <input type="checkbox" name="subServiceType[]" value="{{ $sub->value }}" class="sub-list-checkbox d-none" data-parent="{{ $type->id }}">
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
        <div class="fixed-bottom p-3 py-1 totals-footer" dir="{{$dir}}">
            <div class="ios-container py-0">
                <div class="d-flex justify-content-center">
                    <button id="submitRequestBtnBottom" class="ios-btn px-5 py-2 btn-lg">
                        <i class="bi bi-check-circle me-2"></i> Create Shipping Request
                    </button>
                </div>
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
        };
    </script>
    <script src="{{ asset('js/create-request.js') }}"></script>
</body>
</html>
