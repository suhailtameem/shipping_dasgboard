@php
    use App\Http\Controllers\shipmentsController;
    use App\Http\Controllers\settingsController as Settings;


    $CurranciesTable  = Settings::indexCurrencies();
    $CurrCountrt = 1;

    $ShippingRates  = Settings::indexShippingRates();
    $SHRCounter = 1;

    $CountriesTable  = Settings::indexCountaries();
    $CountCounter = 1;

    $colors = ["#242426","#23A8F2","#FF7224","#FDC136"];

    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl" : "ltr";
    $CenterArText = $lang == "Ar"? "text-center" : " ";

    //Helper functions
    function getDest($shippingType){

        switch ($shippingType) {
            case 1: return shipmentsController::getDestinations('1');
            case 2: return shipmentsController::getDestinations('2') ;
            case 3: return shipmentsController::getDestinations('3');
        }
    }
    function selectList($op1 , $op2){
        if($op1 == $op2) return "selected";
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>
        @lang('lang.Currencies')
    </title>
    <link rel="stylesheet" href="{{asset('css/rates.css')}}">
</head>
<body>
    @include('nav-aside')

    <main class="main-stage">
        <div class="container">
            <!--  Currancies   -->
            <div class="row border-bottom pb-1">
                <div class="col">
                    <h4 class="main-title">
                        <i class="bi bi-currency-exchange mx-2"></i>
                        @lang('lang.Curnces')
                    </h4>
                </div>
                <div class="col"></div>
            </div>
            <div class="row" dir="{{$dir}}">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    @lang('lang.Currancy')
                                </th>
                                <th>
                                    @lang('lang.CurrancyAr')
                                </th>
                                <th>
                                    @lang('lang.UsdRate')
                                </th>
                                <th>
                                    @lang('lang.Date')
                                </th>
                                <th>
                                    @lang('lang.UpdatedAt')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($CurranciesTable as $item)
                                <tr>
                                    <td title="{{$item->id}}">
                                        {{$CurrCountrt++}}
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{$item->currency}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{$item->currency_ar}}</span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm" dir="ltr">
                                            <span class="input-group-text bg-soft-primary text-primary border-0">1 USD =</span>
                                            <span class="form-control form-control-sm border-0 bg-transparent fw-bold text-success">{{$item->usdRate}}</span>
                                            <span class="input-group-text bg-soft-secondary border-0">{{$item->currency}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{date('d M, Y',strtotime($item->created_at))}}
                                    </td>
                                    <td>
                                        {{date('d M, Y',strtotime($item->updated_at))}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!--  Shippings Rates  -->
            <div class="row border-bottom pb-1 mt-5" dir="{{$dir}}">
                <div class="col">
                    <h4 class="main-title">
                        <i class="bi bi-archive-fill"></i>
                        @lang('lang.ShippingsRates')
                        <div class="spinner-border text-primary loading" style="width: 20px; height:20px" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h4>
                </div>
                <div class="col"></div>
            </div>
            <div class="row" dir="{{$dir}}">
                <div class="col">
                    <table class="table table-striped shippingRatesControl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    @lang('lang.ShippingType')
                                </th>
                                <th>
                                    @lang('lang.CountryFrom')
                                </th>
                                <th>
                                    @lang('lang.CountryTo')
                                </th>
                                <th>
                                    @lang('lang.WeightFrom')
                                </th>
                                <th>
                                    @lang('lang.WeightTo')
                                </th>
                                <th>
                                    @lang('lang.Price')
                                </th>
                                <th>
                                    @lang('lang.Date')
                                </th>
                                <th>
                                    @lang('lang.Delete')
                                </th>
                                <th>
                                    @lang('lang.update')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ShippingRates as $item)
                                @php
                                    $SHTYPE =(int) $item->shtype;
                                @endphp
                                <form formaction="{{url('/updateRate/'.$item->id)}}" method="POST">
                                    <tr>
                                        <td style="background: {{$colors[$item->shtype]}}">
                                            {{$SHRCounter++}}
                                            @csrf
                                        </td>
                                        <td>
                                            <select dir="ltr" name="shtype" class="form-select fprm-select-sm ShippingTypeSwitcher" disabled>
                                                <option value="1" {{selectList('1',$item->shtype)}}>
                                                    @lang('lang.AirFreight')
                                                </option>
                                                <option value="2" {{selectList('2',$item->shtype)}}>
                                                    @lang('lang.SeaFreight')
                                                </option>
                                                <option value="3" {{selectList('3',$item->shtype)}}>
                                                    @lang('lang.Land')
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select dir="ltr" name="countryFrom" class="form-select fprm-select-sm shippingCountriesList" disabled>
                                                @if($item->from == null)
                                                    <option value=""></option>
                                                @endif
                                                @foreach (getDest($SHTYPE) as $itemz)
                                                    @php
                                                        $dest = $lang == "Ar" ? $itemz->ar: $itemz->destinations;
                                                    @endphp
                                                    <option value="{{$itemz->id}}" {{selectList($itemz->id,$item->from)}}>
                                                        {{$dest}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select dir="ltr" name="countryTo" class="form-select fprm-select-sm shippingCountriesList" disabled>
                                                @if($item->to == null)
                                                    <option value=""></option>
                                                @endif
                                                @foreach (getDest($SHTYPE) as $itemz)
                                                    @php
                                                        $dest = $lang == "Ar" ? $itemz->ar: $itemz->destinations;
                                                    @endphp
                                                    <option value="{{$itemz->id}}" {{selectList($itemz->id,$item->to)}}>
                                                        {{$dest}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" dir="ltr">
                                                <input type="text" class="form-control form-control-sm {{$CenterArText}}" name="wfrom" value="{{$item->weight_from}}" disabled>
                                                <span class="input-group-text" style="background: {{$colors[$SHTYPE]}}">KG</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm " dir="ltr">
                                                <input type="text" class="form-control form-control-sm {{$CenterArText}}" name="wto" value="{{$item->Weight_to}}" disabled>
                                                <span class="input-group-text" style="background: {{$colors[$SHTYPE]}}">KG</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm d-none" name="unit" list="list{{$item->id}}" value="{{$item->unit}}" disabled>
                                            <datalist id="list{{$item->id}}">
                                                <option selected>KG</option>
                                                <option>G</option>
                                                <option>MG</option>
                                            </datalist>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" dir="ltr">
                                                <span class="input-group-text bg-success text-light border-success">$</span>
                                                <input type="text" class="form-control form-control-sm text-center" name="price" value="{{$item->price}}" disabled>
                                                <span class="input-group-text bg-soft-secondary border-0">USD</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{date('d M, Y' ,strtotime($item->created_at))}}
                                        </td>
                                        <td>
                                            <button
                                                formaction="{{url('/deleteRate/'.$item->id)}}"
                                                class="btn btn-sm btn-danger" disabled>
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button
                                                formaction="{{url('/updateRate/'.$item->id)}}"
                                                class="btn btn-sm btn-primary" disabled>
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <form action="{{url('/addRate')}}" method="POST">
                                <tr>
                                    <td>
                                        #
                                        @csrf

                                    </td>
                                    <td>
                                        <select name="shtype" dir="ltr" class="form-select fprm-select-sm ShippingTypeSwitcher" disabled>
                                            <option value="1">
                                                @lang('lang.AirFreight')
                                            </option>
                                            <option value="2">
                                                @lang('lang.SeaFreight')
                                            </option>
                                            <option value="3">
                                                @lang('lang.Land')
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="countryFrom" dir="ltr" class="form-select fprm-select-sm shippingCountriesList" disabled>
                                            @foreach (getDest($SHTYPE) as $itemz)
                                                @php
                                                    $dest = $lang == "Ar" ? $itemz->ar: $itemz->destinations;
                                                @endphp
                                                <option value="{{$itemz->id}}">
                                                    {{$dest}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="countryTo" dir="ltr" class="form-select fprm-select-sm shippingCountriesList" disabled>
                                            @foreach (getDest($SHTYPE) as $itemz)
                                                @php
                                                    $dest = $lang == "Ar" ? $itemz->ar: $itemz->destinations;
                                                @endphp
                                                <option value="{{$itemz->id}}" >
                                                    {{$dest}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm " dir="ltr">
                                            <input type="text" class="form-control form-control-sm {{$CenterArText}}" name="wfrom" placeholder="@lang('lang.RangeFrom')" disabled>
                                            <span class="input-group-text">KG</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm " dir="ltr">
                                            <input type="text" class="form-control form-control-sm {{$CenterArText}}" name="wto" placeholder="@lang('lang.RangeTo')"  disabled>
                                            <span class="input-group-text">KG</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm d-none" name="unit" list="list{{$item->id}}" value="KG" disabled>
                                        <datalist id="list{{$item->id}}">
                                            <option selected>KG</option>
                                            <option>G</option>
                                            <option>MG</option>
                                        </datalist>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm" dir="ltr">
                                            <span class="input-group-text bg-success text-light border-success">$</span>
                                            <input type="text" class="form-control form-control-sm text-center" name="price" value="" placeholder="@lang('lang.Price')" disabled>
                                            <span class="input-group-text bg-soft-secondary border-0">USD</span>
                                        </div>
                                    </td>

                                    <td colspan="3">
                                        <button class="btn btn-sm btn-success w-100" disabled>
                                            @lang('lang.AddNew')
                                        </button>
                                    </td>
                                </tr>
                            </form>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row d-none">
                <div class="col">
                    <select class="form-select" id="airDests">
                        @foreach (getDest(1) as $item)
                            <option value="{{$item->id}}" {{selectList('1',$item->id)}}>{{$item->destinations}}</option>
                        @endforeach
                    </select>

                    <select class="form-select" id="seaDests">
                        @foreach (getDest(2) as $item)
                            <option value="{{$item->id}}" {{selectList('1',$item->id)}}>{{$item->destinations}}</option>
                        @endforeach
                    </select>

                    <select class="form-select" id="landDests">
                        @foreach (getDest(3) as $item)
                            <option value="{{$item->id}}" {{selectList('1',$item->id)}}>{{$item->destinations}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!--  Countries Lists    -->
            <div class="row border-bottom pb-1 mt-5" dir="{{$dir}}">
                <div class="col">
                    <h4 class="main-title">
                        <i class="bi bi-flag-fill"></i>
                        @lang('lang.CountriesLists')
                    </h4>
                </div>
                <div class="col text-end">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCountryModal">
                        <i class="bi bi-plus-circle-fill me-1"></i> @lang('lang.AddNewCountryWithCurrency')
                    </button>
                </div>
            </div>
            <div class="row" dir="{{$dir}}">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    @lang('lang.Country')
                                </th>
                                <th>
                                    @lang('lang.CountryAr')
                                </th>
                                <th>
                                    @lang('lang.Status')
                                </th>
                                <th>
                                    @lang('lang.Currancy')
                                </th>
                                <th>
                                    @lang('lang.CurrancyAr')
                                </th>
                                <th>
                                    @lang('lang.UsdRate')
                                </th>
                                <th>
                                    @lang('lang.Date')
                                </th>
                                <th>
                                    @lang('lang.Delete')
                                </th>
                                <th>
                                    @lang('lang.update')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($CountriesTable as $item)
                                <form formaction="{{'/updateCountry/'.$item->id}}" method="POST">
                                    <tr>
                                        <td title="{{$item->id}}">
                                            {{$CountCounter++}}
                                            @csrf
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="cname" value="{{$item->name}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="caname" value="{{$item->arabic}}">
                                        </td>
                                        <td>
                                            <select name="cstatus" class="form-select fprm-select-sm" dir="ltr">
                                                <option value="1" {{selectList('1',$item->status)}}>
                                                    @lang('lang.Enable')
                                                </option>
                                                <option value="2" {{selectList('2',$item->status)}}>
                                                    @lang('lang.Disable')
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="curr" value="{{ $item->currency->currency ?? '' }}" placeholder="Currency En">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="curr_ar" value="{{ $item->currency->currency_ar ?? '' }}" placeholder="Currency Ar">
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" dir="ltr">
                                                <span class="input-group-text bg-primary text-light border-primary">1$ =</span>
                                                <input type="number" step="0.0001" class="form-control form-control-sm" name="usdRate" value="{{ $item->currency->usdRate ?? '1.0000' }}">
                                            </div>
                                        </td>
                                        <td>
                                            {{date('D d M, Y',strtotime($item->created_at))}}
                                        </td>
                                        <td>
                                            <button
                                            formaction="{{'/deleteCountry/'.$item->id}}"
                                                class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button
                                            formaction="{{'/updateCountry/'.$item->id}}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Country Modal -->
        <div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark text-light border-secondary">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="addCountryModalLabel">
                            <i class="bi bi-plus-circle-fill text-success me-2"></i>
                            @lang('lang.AddNewCountryWithCurrency')
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{url('/addCountry')}}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Country Info -->
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">@lang('lang.Country') (En)</label>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-light border-0" name="cname" placeholder="e.g. United Arab Emirates" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">@lang('lang.Country') (Ar)</label>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-light border-0" name="caname" placeholder="مثال: الإمارات" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">@lang('lang.Status')</label>
                                    <select name="cstatus" class="form-select form-select-sm bg-secondary text-light border-0" dir="ltr">
                                        <option value="1">@lang('lang.Enable')</option>
                                        <option value="2">@lang('lang.Disable')</option>
                                    </select>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <h6 class="border-bottom pb-2 border-secondary text-primary">
                                        <i class="bi bi-currency-exchange me-1"></i> Currency Details
                                    </h6>
                                </div>

                                <!-- Currency Info -->
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">@lang('lang.Currancy') (En)</label>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-light border-0" name="curr" placeholder="e.g. AED" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">@lang('lang.Currancy') (Ar)</label>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-light border-0" name="curr_ar" placeholder="مثال: درهم" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">@lang('lang.UsdRate')</label>
                                    <div class="input-group input-group-sm" dir="ltr">
                                        <span class="input-group-text bg-primary text-light border-0">1$ =</span>
                                        <input type="number" step="0.0001" class="form-control form-control-sm bg-secondary text-light border-0" name="usdRate" value="1.0000" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('lang.Cancel')</button>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-save-fill me-1"></i> @lang('lang.AddNew')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</body>
@include('scripts')
<script src="{{asset('js/rates.js')}}"></script>
</html>
