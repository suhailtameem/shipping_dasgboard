@php
    use App\Http\Controllers\shipmentsController;
    $shipment =shipmentsController::getShipmentID(request()->id);
    $movements =shipmentsController::getMovements(request()->id);

    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl" : "ltr";
    $CenterArText = $lang == "Ar"? "text-center" : " ";

    if (count($shipment)>0) {
        $shipment= $shipment->first();
    }else{
        exit('There is no shipment with this ID');
    }

    $ShippingType = $shipment->sh_type;
    $AutoProgress = shipmentsController::movesProgress($shipment->id);
    $ManualProgress = $shipment->progress;
    $progress = $shipment->pauto == '1' ? $AutoProgress : $ManualProgress;


    /*========  Helper Functions  ========*/
    function selectOption($o1,$o2){
        if($o1 == $o2){
            return "selected";
        }
    }

    function checked($a1,$a2){
        if($a1 == $a2){
            return "checked";
        }
    }

    function shType($shid){
        switch ($shid) {
            case '1':
                return [ __('lang.AirFreight'),'Cargos','<i class="bi bi-send-fill"></i>','/air-freight'];

            case '2':
                return [__('lang.SeaFreight'),'Containers','<i class="las la-ship"></i>','/sea-freight'];

            case '3':
                return [__('lang.Land'),'Shipment','<i class="las la-shipping-fast"></i>','/land-transport'];

            default:
                # code...
                break;
        }
    }

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>
        @lang('lang.ShipmentMovements')
    </title>
    <link rel="stylesheet" href="{{asset('css/shipping.css')}}">
</head>
<body>
    @include('nav-aside')
    @php

        $destnations =shipmentsController::getDestinations($ShippingType);
        $SHINFO = shType($ShippingType);
    @endphp
    <main class="main-stage">
        <div class="container-fluid" dir="{{$dir}}">
            <div class="row border-top border-bottom py-2">
                <div class="col-md-8 sol-sm-12">
                    <div class="main-title d-inline" dir="ltr">
                        <span>
                            {!! $SHINFO[2] !!} {{$SHINFO[0]}}
                        </span>
                        /
                        <span>
                            <a href="{{url(request()->lang.''.$SHINFO[3])}}" class="links text-primary">
                                {{$SHINFO[1]}}
                                <i class="bi bi-box-seam mx-1"></i>
                            </a>
                        </span>
                        /


                        <span class="text-success">
                                {{$shipment->container}}
                        </span> /
                        <span class="text-muted">
                            <i class="bi bi-calendar2-event mx-1"></i>
                            {{date("D, d M Y",strtotime($shipment->created_at))}}
                        </span>
                    </div>
                    <div
                        style="width: 20px; height: 20px"
                        class="spinner-border text-primary spinners" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 d-flex justify-content-end">
                    <div class="badge  bg-dark">{{$progress}}%</div>
                    <button data-bs-toggle="modal" data-bs-target="#newShippment" class="btn btn-sm btn-primary floating-btn">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 col-sm-12 mb-3">
                    <h4 class="table-title">
                        <i class="bi bi-map-fill me-2"></i>
                        @lang('lang.ShipmentMovements')
                    </h4>
                    <div class="table-responsive">
                        <table class="table  table-striped table-hover border-top mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">
                                        @lang('lang.Dates')
                                    </th>
                                    <th style="width: 30%">
                                        @lang('lang.Details')
                                    </th>
                                    <th style="width: 20%">
                                        @lang('lang.Location')
                                    </th>
                                    <th style="width: 15%">
                                        @lang('lang.CreatedAt')
                                    </th>
                                    <th style="width: 5%" style="text-center">
                                        @lang('lang.Delete')
                                    </th>
                                    <th style="width: 5%" style="text-center">
                                        @lang('lang.Edit')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($movements as $item)
                                    <form formaction="{{url('/updateMovement')}}" method="post">
                                        <tr>
                                            <td>
                                                {{$counter++}}
                                                @csrf
                                                <input type="hidden" name="mid" value="{{$item->id}}">
                                            </td>
                                            <td>
                                                <input type="date" name="date" value="{{date('Y-m-d',strtotime($item->step_date))}}" class="form-control form-control-sm text-control">
                                            </td>
                                            <td>
                                                <input type="text" name="details" value="{{$item->details}}" placeholder="@lang('lang.EnterDetails')" class="form-control form-control-sm text-control">
                                            </td>
                                            <td>
                                                <input type="text" name="locations" value="{{$item->location}}"  placeholder="@lang('lang.EnterLocation')" class="form-control form-control-sm text-control">
                                            </td>
                                            <td>
                                                {{date("D, d M Y",strtotime($item->created_at))}}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" formaction="{{url('/delMovement/'.$item->id)}}">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" formaction="{{url('/updateMovement')}}">
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

            <div class="row mt-4">
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="row">
                        <div class="col">
                            <h4 class="table-title">
                                <i class="bi bi-map-fill me-2"></i>
                                @lang('lang.AddMoreMoves')
                            </h4>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <button class="btn btn-sm btn-secondary mx-2 dis" id="newLine" disabled>
                                <i class="bi bi-list-nested"></i>
                                @lang('lang.MoreLines')
                                <div
                                    style="width: 15px; height: 15px"
                                    class="spinner-border text-light spinners" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>

                            <label for="addMovements" class="btn btn-sm btn-success ">
                                <i class="bi bi-hdd-fill"></i>
                                @lang('lang.SaveMoves')
                            </label>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <form action="/addMovements" method="POST">
                            @csrf
                            <input type="submit" id="addMovements" class="d-none">
                            <table class="table  table-striped table-hover border-top mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">
                                        @lang('lang.Dates')
                                    </th>
                                    <th style="width: 40%">
                                        @lang('lang.Details')
                                    </th>
                                    <th style="width: 30%">
                                        @lang('lang.Location')
                                    </th>
                                    <th style="width: 5%" style="text-center">
                                        @lang('lang.Delete')
                                    </th>
                                </tr>
                            </thead>
                                <tbody id="linesTable">
                                    <tr>
                                        <td>#</td>
                                        <td>
                                            <input type="hidden" name="shid[]" value="{{request()->id}}">
                                            <input type="date" name="date[]" class="form-control form-control-sm text-control">
                                        </td>
                                        <td>
                                            <input type="text" name="details[]" placeholder="@lang('lang.EnterDetails')" class="form-control form-control-sm text-control">
                                        </td>
                                        <td>
                                            <input type="text" name="locations[]" placeholder="@lang('lang.EnterLocation')" class="form-control form-control-sm text-control">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger removeLine">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<!-- Update Shipment Modal -->
<div class="modal fade" id="newShippment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content" dir="{{$dir}}">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            <i class="bi bi-plus-square-dotted"></i>
            @lang('lang.EditShipment')
        </h5>
        <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form action="{{url('/updateShipment')}}" method="POST">
                @csrf
                <input type="hidden" name="shid" value="{{request()->id}}">
                <div class="mb-3">
                    <label class="form-label">
                        @lang('lang.ShipmentName')
                    </label>
                    <input type="text" name="conName" class="form-control text-control"  placeholder="Enter Shipment Name" value="{{$shipment->container}}">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        @lang('lang.CompletionPercentage') <b id="percent">{{$shipment->progress}}%</b>
                    </label>
                    <input type="range" name="progress" class="form-range" max="100" min="0" id="range" value="{{$shipment->progress}}" step="1">
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
                                <input class="form-check-input" type="radio" name="pauto" value="1" {{checked('1',$shipment->pauto)}} id="autoOn">
                                <label class="form-check-label" for="autoOn">
                                    @lang('lang.On')
                                </label>
                            </div>

                        </div>
                        <div class="col mb-1" dir="ltr">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pauto" value="0" {{checked('0',$shipment->pauto)}} id="autoOff">
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
                        @foreach ($destnations as $item)
                            @php
                                $dest = $lang == "Ar"? $item->ar : $item->destinations;
                            @endphp
                            <option value="{{$item->id}}" {{selectOption($item->id,$shipment->from)}}>
                                {{$dest}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        @lang('lang.RecivingCountry')
                    </label>
                    <select class="form-select {{$CenterArText}}" name="desTo">
                        @foreach ($destnations as $item)
                            @php
                                $dest = $lang == "Ar"? $item->ar : $item->destinations;
                            @endphp
                            <option value="{{$item->id}}" {{selectOption($item->id,$shipment->to)}}>
                                {{$dest}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" name="" id="submit" class="d-none">
            </form>

            <form action="{{url('/delShipment')}}" method="POST" class="d-flex justify-content-center">
                @csrf
                <input type="hidden" name="shid" value="{{request()->id}}">
                <input type="hidden" name="lang" value="{{request()->lang}}">
                <div class="mb-3">
                    <button class="btn btn-light text-danger">
                        @lang('lang.DelShipment')
                    </button>
                </div>
            </form>
        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            @lang('lang.Close')
        </button>
        <label for="submit" class="btn btn-primary">
            @lang('lang.EditShipment')
        </label>
        </div>
    </div>
    </div>
</div>
</body>
@include('scripts')
<script src="{{asset('js/shipping.js')}}"></script>
</html>
