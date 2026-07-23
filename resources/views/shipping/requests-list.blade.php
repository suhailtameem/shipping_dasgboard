<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>
        @lang('lang.ShippingRequests')
    </title>
    <link rel="stylesheet" href="{{asset('css/shipping.css')}}">
</head>
<body>
    @include('nav-aside')
@php
    $counter = 1;
@endphp

    <main class="main-stage">
        <div class="container-fluid" dir="{{$dir}}">
            <div class="row border-bottom pb-1">
                <div class="col">
                    <a href="{{url('/'.$lang.'/request-list')}}" class="links">
                        <h4 class="main-title">
                            <i class="bi bi-inbox-fill mx-2"></i>
                            {{count($ShippingRequests)}}
                            @lang('lang.ShippingRequests')
                            <div class="spinner-border text-primary mx-2 loading" style="width: 20px; height:20px" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </h4>
                    </a>
                </div>

                <div class="col d-flex justify-content-end">

                    <a
                        href="{{url('/' . $lang . '/create-request')}}"
                        class="links btn btn-sm btn-primary text-light float-end eq-btn ms-2">
                        <i class="bi bi-plus-circle mx-1"></i>
                        @lang('lang.NewShipping')
                    </a>
                    
                    <!-- <button
                        data-bs-toggle="modal"
                        data-bs-target="#NSHR"
                        class="links btn btn-sm btn-secondary text-light float-end eq-btn ms-2">
                        <i class="bi bi-plus-circle-dotted mx-1"></i>
                        @lang('lang.NewShipping')
                    </button> -->

                    <button
                        data-bs-toggle="modal"
                        data-bs-target="#Filters"
                        class="links btn btn-sm btn-secondary text-light mx-2 float-end eq-btn">
                        <i class="bi bi-funnel-fill mx-1"></i>
                        @lang('lang.Filter')
                    </button>

                    <button class="links btn btn-sm btn-secondary text-light mx-1 float-end search eq-btn" alt="false">
                        <i class="bi bi-search mx-1"></i>
                        @lang('lang.Search')
                    </button>

                    @if(request()->has('action'))
                        <a href="{{url('/'.$lang.'/request-list')}}" class="links btn btn-sm btn-danger text-light mx-1 float-end" >
                            <i class="bi bi-funnel-fill mx-1"></i>
                            Cancel fillters
                        </a>
                    @endif
                </div>
            </div>


            <div class="row mt-3">
                <div class="col">
                    <form formaction="{{url('/upRequestBasic')}}" method="POST">
                        @csrf
                        <input type="submit" formaction="/upRequestBasic" id="updateBasic" class="d-none">
                        <input type="submit" formaction="/delRequestBasic" id="deleteBasic" class="d-none">

                        
                        <table class="table table-striped table-hover dataTable">
                            <thead class="thSizeing">
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="7%">TNO</th>
                                    <th width="10%">
                                        @lang('lang.Customer')
                                    </th>
                                    <th width="8%" class="text-center">
                                        @lang('lang.FromTo')
                                    </th>
                                    <th width="10%">
                                        @lang('lang.Container')
                                    </th>
                                    <th width="10%">
                                        @lang('lang.Weight')
                                    </th>
                                    <th width="5%">
                                        @lang('lang.Content')
                                    </th>
                                    <th width="8%">
                                        @lang('lang.ReqDate')
                                    </th>
                                    <th width="12%" class="highlight">
                                        @lang('lang.ReqStatus')
                                    </th>
                                    <th width="11%" class="highlight">
                                        @lang('lang.Shipping')
                                    </th>
                                    <th width="11%" class="highlight">
                                        @lang('lang.Shipment')
                                    </th>
                                    <th width="5%" class="text-center">
                                        <input class="form-check-input unlock-all" type="checkbox" disabled>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="thSizeing rows-prefix" id="request-list">
                                @foreach($ShippingRequests as $Ritem)
                                    @php
                                        $RID = $Ritem->id;
                                    @endphp
                                    <tr style="border-left-color:{{ $controller->statusColors($Ritem->req_status) }};">
                                        <th title="{{$RID}}">
                                            {{$counter++}}
                                        </th>
                                        <td>
                                            {{$Ritem->tno}}
                                        </td>
                                        <td>
                                            <div class="text-limit" >
                                                @if($Ritem->customer)
                                                    <a
                                                        href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#customer-model-{{$Ritem->cid}}"
                                                        title="View Customer Profile"
                                                        class="links names w-100 ">
                                                        {{ $Ritem->customer->first.' '.$Ritem->customer->last}}
                                                    </a>
                                                @else
                                                    <span class="text-muted small">@lang('lang.NoCustomerAssigned')</span>
                                                @endif
                                            </div>
                                            <!-- Customer Profile Modal Integration -->
                                            @if($Ritem->customer)
                                                <x-customer-profile-modal
                                                    :id="$Ritem->cid"
                                                    :customer="$Ritem->customer"
                                                    :dir="$dir"
                                                    :countries="$CountriesList"
                                                />
                                            @endif
                                            <!-- End Modal -->
                                        </td>
                                         <td class="text-center">
                                             <div class="text-limit">{{ $lang == "Ar" ? ($Ritem->fromDest->ar ?? $Ritem->from) : ($Ritem->fromDest->destinations ?? $Ritem->from) }}</div>
                                             <div class="text-limit">{{ $lang == "Ar" ? ($Ritem->toDest->ar ?? $Ritem->to) : ($Ritem->toDest->destinations ?? $Ritem->to) }}</div>
                                         </td>
                                        <td>
                                            <select name="containerType_{{$RID}}" dir="ltr" class="form-select form-select-sm" disabled>
                                                @foreach ($ContainerTypesList[1] as $item)
                                                    <option value="{{$item->value}}" {{ $item->value == $Ritem->containerized ? 'selected' : '' }}>
                                                        {{$lang == "Ar"? $item->ar : $item->en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm " dir="ltr">
                                                <input type="text" class="form-control weightInputs" name="totalWeight_{{$RID}}" value="{{$Ritem->total_weight}}" disabled>
                                                <span class="input-group-text">KG</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{url('/'.request()->lang.'/request/'.$Ritem->id)}}"  class="links btn btn-sm btn-light ">
                                                @lang('lang.Manage')
                                            </a>
                                        </td>
                                        <td>
                                            {{date('d/m/Y',strtotime($Ritem->created_at))}}
                                        </td>
                                        <td class="highlight">
                                            <select dir="ltr" name="reqStatus_{{$RID}}" style="background:{{ $controller->statusColors($Ritem->req_status) }}" class=" form-select form-select-sm selectColor" title="{{$Ritem->Comment}}" disabled>
                                                @foreach ($ReqStatusList[1] as $item)
                                                    <option value="{{$item->value}}" {{ $item->value == $Ritem->req_status ? 'selected' : '' }}>
                                                        {{$lang == "Ar"? $item->ar : $item->en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                         <td class="highlight">
                                             {{ $lang == "Ar" ? ($Ritem->shippingType->ar ?? $Ritem->sh_type) : ($Ritem->shippingType->en ?? $Ritem->sh_type) }}
                                         </td>
                                        <td class="highlight">
                                            <select dir="ltr" name="shid_{{$RID}}" title="{{$Ritem->sh_type}}" alt="{{$Ritem->shid}}" class="form-select form-select-sm shipmentsLists" disabled>
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input unlock-row" type="checkbox"  name="CheckBox[]" value="{{$RID}}" disabled>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center text-secondary">
                                        <span class="tableTotalWeight"></span>/KG
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tfoot>
                        </table>
                    </form>

                    <div class="snackbar text-end">
                        <div class="row">
                            <div class="col-md-3 col-sm-4">
                                <form action="?" method="GET">
                                    <div class="input-group input-group-sm ">
                                        <input type="hidden" name="action" value="1">
                                        <input type="text" class="form-control" placeholder="Enter Tracking Number" name="TNO">
                                        <button class="btn btn-sm btn-primary">
                                            <i class="bi bi-search"></i>
                                            Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col">
                                <label for="deleteBasic" class="btn btn-sm btn-danger mx-2">
                                    <i class="bi bi-trash-fill mx-1"></i>
                                    Delete Selected
                                </label>

                                <label for="updateBasic" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-fill mx-1"></i>
                                    Update Selected
                                </label>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



    <!-- New Request Model -->
    <div class="modal fade" id="NSHR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" dir="{{$dir}}">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle-dotted mx-1"></i>
                        @lang('lang.NewShipping')
                    </h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('/newShipment')}}" method="post">
                        <div class="mb-3">
                            <label class="form-label">
                                @lang('lang.ShippingType')
                            </label>
                            <select name="shippType" class="form-select form-select-sm ShippingTypeSwitcher {{$CenterArText}}">
                                @foreach ($ShippingTypesList[1] as $item)
                                    <option value="{{$item->value}}">
                                        {{$lang == 'Ar'? $item->ar :$item->en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                @lang('lang.ContainerType')
                            </label>
                            <div class="input-group input-group-sm" dir="ltr">
                                <span class="input-group-text">
                                    <i class="bi bi-box2-fill"></i>
                                </span>
                                <select name="containerType" class="form-select form-select-sm {{$CenterArText}}">
                                    @foreach ($ContainerTypesList[1] as $item)
                                        <option value="{{$item->value}}">
                                            {{$lang == 'Ar'? $item->ar :$item->en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                @lang('lang.ServiceType')
                            </label>
                            <select name="serviceType" class="form-select form-select-sm {{$CenterArText}}">
                                @foreach ($ServicesTypesList[1] as $item)
                                    <option value="{{$item->value}}">
                                        {{$lang == 'Ar'? $item->ar :$item->en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                @lang('lang.SendingCountry')
                            </label>
                            <div class="input-group input-group-sm" dir="ltr">
                                <span class="input-group-text text-success">
                                    <i class="bi bi-arrow-up-square-fill"></i>
                                </span>
                                <select  name="fromCountry" class="form-select form-select-sm countriesLists {{$CenterArText}}">
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                @lang('lang.RecivingCountry')
                            </label>
                            <div class="input-group input-group-sm" dir="ltr">
                                <span class="input-group-text text-primary">
                                    <i class="bi bi-arrow-down-square-fill"></i>
                                </span>
                                <select name="toCountry" class="form-select form-select-sm countriesLists {{$CenterArText}}">
                                </select>
                            </div>
                        </div>

                        @csrf
                        <input type="hidden" name="getway" value="1" title="admin">
                        <input type="hidden" name="getwayType" value="1" title="web">
                        <input type="hidden" name="cuid" value="{{Session::get('user')->id}}">

                        <input type="hidden" name="lang" value="{{$lang}}" title="">
                        <input type="submit" class="d-none" id="createReuest">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        @lang('lang.Close')
                    </button>
                    <label for="createReuest" class="btn btn-primary">
                        @lang('lang.CreateRequest')
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Destnations List --->
    <div class="row d-none">
        <div class="col">
            <select class="form-select" id="airDests">
                @foreach ($controller->getDestInfo(1) as $item)
                    <option
                        value="{{$item->id}}"
                         >
                            {{$lang == "Ar"?$item->ar : $item->destinations}}
                        </option>
                @endforeach
            </select>

            <select class="form-select" id="seaDests">
                @foreach ($controller->getDestInfo(2) as $item)
                    <option
                        value="{{$item->id}}"
                         >
                            {{$lang == "Ar"?$item->ar : $item->destinations}}
                        </option>
                @endforeach
            </select>

            <select class="form-select" id="landDests">
                @foreach ($controller->getDestInfo(3) as $item)
                    <option
                        value="{{$item->id}}"
                         >
                            {{$lang == "Ar"?$item->ar : $item->destinations}}
                        </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Shipments Lista -->
    <div class="row d-none">
        <div class="col">

            <select id="AirCargos">
                @foreach ($AirCargos as $item)
                    <option value="{{$item->id}}">
                        {{$item->container}}
                    </option>
                @endforeach
                <option value="0">All</option>
                <option value="-1"></option>
            </select>

            <select id="SeaContainers">
                @foreach ($SeaContainers as $item)
                    <option value="{{$item->id}}">
                        {{$item->container}}
                    </option>
                @endforeach
                <option value="0">All</option>
                <option value="-1"></option>
            </select>

            <select id="LandCharges">
                @foreach ($LandCharges as $item)
                    <option value="{{$item->id}}">
                        {{$item->container}}
                    </option>
                @endforeach
                <option value="0">All</option>
                <option value="-1"></option>
            </select>

        </div>
    </div>



    <!-- Filters & Search Moddel -->
    <div class="modal fade" id="Filters" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-funnel-fill"></i>
                        Filter & Search
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?" method="get">
                        {{-- @csrf --}}
                        <input type="submit" class="d-none" id="submitFilter">
                        <input type="hidden" name="action" value="2">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input filter-toggler" type="checkbox" name="applyStatus" id="applyStatus" {{ $applyStatus ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="applyStatus">@lang('lang.ReqStatus')</label>
                            </div>
                            <select name="reqStatus" id="filter_applyStatus" class="form-select form-select-sm" {{ !$applyStatus ? 'disabled' : '' }}>
                                <option value="0">-</option>
                                @foreach ($ReqStatusList[1] as $item)
                                    <option value="{{$item->value}}" {{ $item->value == $ReqState ? 'selected' : '' }}>
                                        {{$lang == "Ar"? $item->ar : $item->en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input filter-toggler" type="checkbox" name="applyType" id="applyType" {{ $applyType ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="applyType">@lang('lang.ShippingType')</label>
                            </div>
                            <select name="SHTYPE" id="filter_applyType" class="form-select form-select-sm ShipSelector" {{ !$applyType ? 'disabled' : '' }}>
                                <option value="0">-</option>
                                @foreach ($ShippingTypesList[1] as $item)
                                    <option value="{{$item->id}}" {{ $item->id == $ShipType ? 'selected' : '' }}>
                                        {{$lang == "Ar"? $item->ar : $item->en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input filter-toggler" type="checkbox" name="applyShipment" id="applyShipment" {{ $applyShipment ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="applyShipment">@lang('lang.Shipment')</label>
                            </div>
                            <select name="SHID" id="filter_applyShipment" class="form-select form-select-sm ShipsList" {{ !$applyShipment ? 'disabled' : '' }}>
                                <option value="0">-</option>
                                @if($ShipType == 1)
                                    @foreach ($AirCargos as $item)
                                        <option value="{{$item->id}}" {{ $item->id == $shipment ? 'selected' : '' }}>{{$item->container}}</option>
                                    @endforeach
                                @elseif($ShipType == 2)
                                    @foreach ($SeaContainers as $item)
                                        <option value="{{$item->id}}" {{ $item->id == $shipment ? 'selected' : '' }}>{{$item->container}}</option>
                                    @endforeach
                                @elseif($ShipType == 3)
                                    @foreach ($LandCharges as $item)
                                        <option value="{{$item->id}}" {{ $item->id == $shipment ? 'selected' : '' }}>{{$item->container}}</option>
                                    @endforeach
                                @endif
                                <option value="-1"></option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input filter-toggler" type="checkbox" name="applyDate" id="applyDate" {{ $applyDate ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="applyDate">@lang('lang.DateRange')</label>
                            </div>
                            <div class="row g-2">
                                <div class="col">
                                    <label class="small text-muted mb-0">From</label>
                                    <input type="date" name="DFROM" id="filter_applyDate_From" class="form-control form-control-sm" value="{{$DFrom}}" {{ !$applyDate ? 'disabled' : '' }}>
                                </div>
                                <div class="col">
                                    <label class="small text-muted mb-0">To</label>
                                    <input type="date" name="DTO" id="filter_applyDate_To" class="form-control form-control-sm" value="{{$Dto}}" {{ !$applyDate ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <label for="submitFilter" type="button" class="btn btn-primary">
                        Search
                    </label>
                </div>
            </div>
        </div>
    </div>

</body>
@include('scripts')
<script src="{{asset('js/sh-request.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('Filters');
        if (modal) {
            const togglers = modal.querySelectorAll('.filter-toggler');
            togglers.forEach(toggler => {
                toggler.addEventListener('change', function() {
                    const targetId = 'filter_' + this.id;
                    const inputs = modal.querySelectorAll(`[id^="${targetId}"]`);
                    inputs.forEach(input => {
                        input.disabled = !this.checked;
                        if(!input.disabled && input.classList.contains('ShipSelector')){
                           input.dispatchEvent(new Event('change'));
                        }
                    });
                });
            });
        }
    });
</script>
</html>



