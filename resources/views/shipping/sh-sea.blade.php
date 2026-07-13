@php
    use App\Http\Controllers\shipmentsController;

    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl":"ltr";
    $CenterArText = $lang == "Ar"? "text-center" : " ";

    /* ======== Helper Function =========*/

    function selectOption($o1,$o2){
        if($o1 == $o2){
            return "selected";
        }
    }

    function statusClass($status){
        switch ($status) {
            case '1': return "bg-success text-light";
            case '2': return "bg-danger text-light";
            default: return "";
        }
    }

    function destIs($did){
        $dest = shipmentsController::getDestinationID($did);
        if(count($dest)>0){
            return $dest->first();
        }else{
            return [];
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>
        @lang('lang.SearFTapTitle')
    </title>
    <link rel="stylesheet" href="{{asset('css/shipping.css')}}">
</head>
<body>
    @include('nav-aside')
    @php

        $destnations =shipmentsController::getDestinations('2');
        $activeDest = shipmentsController::getActiveDest('2');
        $dateFrom = date('Y-m-d');
        $dateTo = date('Y-m-d');

        if (isset($_GET['showBy'])) {
            switch ($_GET['showBy']) {
                case 'range':
                    $shipments =shipmentsController::getShipmentsRange('2',$_GET['from'],$_GET['to']);
                    $dateFrom = date('Y-m-d',strtotime($_GET['from']));
                    $dateTo = date('Y-m-d',strtotime($_GET['to']));
                    break;
                case 'all':
                    $shipments =shipmentsController::getShipments('2');
                    break;
            }
        }else{
            $shipments =shipmentsController::getShipments('2');
        }
    @endphp
    <main class="main-stage">
        <div class="container-fluid">
            <div class="row border-top border-bottom py-2">
                <div class="col-md-3 sol-sm-12">
                    <h4 class="main-title">
                        <i class="las la-ship f32"></i>
                        @lang('lang.SearFTapTitle') / Containers ({{count($shipments)}})
                    </h4>
                </div>


                <div class="col-md-7 col-sm-12 mb-2 ">
                    <form action="?" method="GET" class="w-100 h-100 ">
                        <div class="input-group input-group-sm mx-2 ">
                            <span class="input-group-text">
                                <i class="bi bi-calendar2-event mx-2"></i>
                                @lang('lang.From')
                            </span>
                            <input type="date" name="from" class="form-control form-control-sm" value="{{$dateFrom}}">
                            <span class="input-group-text">
                                <i class="bi bi-calendar2-event mx-2"></i>
                                @lang('lang.To')
                            </span>
                            <input type="date" name="to"  class="form-control form-control-sm" value="{{$dateTo}}">
                            <button class="btn btn-sm btn-primary" name="showBy" value="range">
                                <i class="bi bi-filter"></i>
                                @lang('lang.Filter')
                            </button>
                            <input type="submit" value="all" name="showBy" class="d-none" id="all">
                        </div>
                    </form>
                </div>

                <div class="col-md-2 col-sm-12 d-flex justify-content-center align-items-center mb-2">
                    <label for="all" class="btn btn-sm btn-primary">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        @lang('lang.AllShipments')
                    </label>
                    <button class="btn btn-sm btn-dark text-light floating-btn fbtn-l2" data-bs-toggle="modal" data-bs-target="#destnationsModel" title="Shipments Destinations">
                        <i class="bi bi-compass"></i>
                    </button>
                    <button data-bs-toggle="modal" data-bs-target="#newShippment" title="Add a new shipment" class="btn btn-sm btn-success floating-btn">
                        <i class="bi bi-plus-square-dotted"></i>
                    </button>
                </div>

            </div>

            <div class="row mt-4">
                @foreach ($shipments as $item)
                    @php
                        $AutoProgress = shipmentsController::movesProgress($item->id);
                        $ManualProgress = $item->progress;
                        $progress = $item->pauto == '1' ? $AutoProgress : $ManualProgress;
                        $progClass = $progress<100 ? "warning" : "success";
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                        <div class="sh-item orange-item">
                            <div class="list-tile">
                                <div class="tile-content">
                                    <h4 class="list-title">
                                        {{$item->id}} - {{$item->container}}
                                    </h4>
                                    <small class="list-subtitle">
                                        <i class="bi bi-calendar2-event me-2"></i>
                                        {{date('D, d M Y' ,strtotime($item->created_at))}}
                                    </small>
                                </div>
                                <div class="tile-leading">
                                    {{$progress}}%
                                </div>
                            </div>
                            <div class="destnations border-top border-bottom">
                                @php
                                    $From = $lang == "Ar" ?destIs($item->from)->ar : destIs($item->from)->destinations;
                                    $To = $lang == "Ar" ?destIs($item->to)->ar : destIs($item->to)->destinations;
                                @endphp
                                {{$From}}/{{$To}}

                                <div class="progress-area mt-2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$progClass}}"
                                            role="progressbar"
                                            aria-valuenow="{{$progress}}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{$progress}}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center   w-100">
                                <a href="{{url(request()->lang.'/movements/'.$item->id.'/'.$item->sh_type)}}" class="btn btn-sm btn-outline-secondary mb-3 mt-3 w-50 links">
                                    <i class="bi bi-archive-fill"></i>
                                    @lang('lang.ManageShipment')
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- New Shipment Modal -->
    <div class="modal fade" id="newShippment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="bi bi-plus-square-dotted"></i>
                @lang('lang.NewShipment')
            </h5>
            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/addShipment')}}" method="POST">

                    <div class="mb-3">
                        <label class="form-label">
                            @lang('lang.ShipmentName')
                        </label>
                        @csrf
                        <input type="hidden" name="shType" value="2">
                        <input type="text" name="conName" class="form-control text-control"  placeholder="@lang('lang.ShipmentNameHolder')">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            @lang('lang.CompletionPercentage') <b id="percent">0%</b>
                        </label>
                        <input type="range" name="progress" class="form-range" max="100" min="0" id="range" value="0" step="1">
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col mb-1">
                                <label class="form-label">
                                    @lang('lang.AutoProgress')
                                </label>
                            </div>
                            <div class="col mb-1" dir="ltr">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pauto" value="1" id="autoOn" checked>
                                    <label class="form-check-label" for="autoOn">
                                        @lang('lang.On')
                                    </label>
                                </div>

                            </div>
                            <div class="col mb-1" dir="ltr">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pauto" value="0" id="autoOff">
                                    <label class="form-check-label" for="autoOff">
                                        @lang('lang.Off')
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            @lang('lang.SendingCountry')
                        </label>
                        <select class="form-select {{$CenterArText}}" name="desFrom">
                            @foreach ($activeDest as $item)
                                @php
                                    $dest = $lang == "Ar"? $item->ar : $item->destinations;
                                @endphp
                                <option value="{{$item->id}}">{{$dest}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            @lang('lang.RecivingCountry')
                        </label>
                        <select class="form-select {{$CenterArText}}" name="desTo">
                            @foreach ($activeDest as $item)
                                @php
                                    $dest = $lang == "Ar"? $item->ar : $item->destinations;
                                @endphp
                                <option value="{{$item->id}}">{{$dest}}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="submit" name="" id="submit" class="d-none">
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                @lang('lang.Close')
            </button>
            <label for="submit" class="btn btn-primary">
                @lang('lang.SaveShipment')
            </label>
            </div>
        </div>
        </div>
    </div>

    <!-- Destnations Modal -->
    <div class="modal fade" id="destnationsModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="{{$dir}}">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="bi bi-plus-square-dotted"></i>
                @lang('lang.ShipmentsDestnations')
            </h5>
            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/addDestnation')}}" method="POST" dir="ltr">
                    <div class="input-group mb-3">
                        @csrf
                        <input type="hidden" name="shType" value="2">
                        <input type="text" class="form-control" name="shDest" dir="ltr" placeholder="@lang('lang.EnterDestnation')">
                        <input type="text" class="form-control" name="ar" dir="rtl" placeholder="@lang('lang.EnterArDest')">
                        <button class="btn btn-success" >
                            @lang('lang.AddDesnation')
                        </button>
                    </div>
                </form>

                <table class="table table-sm">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">
                            @lang('lang.Destnation')
                        </th>
                        <th style="width: 30%">
                            @lang('lang.Arabic')
                        </th>
                        <th style="width: 20%">
                            @lang('lang.Status')
                        </th>
                        <th style="width: 5%">
                            @lang('lang.Addresses')
                        </th>
                        <th style="width: 5%" class="text-center">
                            @lang('lang.Del')
                        </th>
                        <th style="width: 5%" class="text-center">
                            @lang('lang.update')
                        </th>
                    </tr>
                    @php
                        $Counter = 1;
                    @endphp
                    @foreach ($destnations as $item)
                        <form formaction="{{url('/upDestnation')}}" method="post">
                            <tr>
                                <td>
                                    @csrf
                                    {{$Counter++}}
                                    <input type="hidden" name="did" value="{{$item->id}}">
                                </td>
                                <td>
                                    <input
                                        type="text" class="form-control form-control-sm"
                                        placeholder="@lang('lang.EnterDestnation')"
                                        name="shDest"
                                        dir="ltr"
                                        value="{{$item->destinations}}"
                                        required>
                                </td>
                                <td>
                                    <input
                                        type="text" class="form-control form-control-sm"
                                        placeholder="@lang('lang.EnterArDest')"
                                        name="ar"
                                        value="{{$item->ar}}"
                                        required>
                                </td>
                                <td>
                                    <select
                                        name="status"
                                        class="form-select form-select-sm {{$CenterArText}} {{statusClass($item->status)}}" >
                                        <option value="1" {{selectOption('1',$item->status)}}>
                                            @lang('lang.Enable')
                                        </option>
                                        <option value="2" {{selectOption('2',$item->status)}}>
                                            @lang('lang.Disable')
                                        </option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-dark" target="_blank" href="{{url(request()->lang.'/addresses/'.$item->id)}}">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger" formaction="{{url('/delDestnaion/'.$item->id)}}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" formaction="{{url('/upDestnation')}}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </form>
                    @endforeach

                </table>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                @lang('lang.Close')
            </button>
            {{-- <label for="submit" class="btn btn-primary">Save Shipment</label> --}}
            </div>
        </div>
        </div>
    </div>

</body>


@include('scripts')
</html>
