@php
use App\Http\Controllers\requestsControllrt;
use App\Http\Controllers\shipmentsController;

$containerInformation = [];
$shipmentMoves = [];
$shipmentMovesCounter = [];
$result = 0; //0 normal - 1 result  - (-1) no result - (-2) Not shipped yet

$shippmentComplateProgress = 0;
$searchBar = '';

if (isset($_GET['TNO'])) {
    $searchBar = $_GET['TNO'];
    $requestData = requestsControllrt::getShipmentRequestBy($searchBar);
    if (count($requestData) > 0) {
        $result = 1;
        foreach ($requestData as $key) {
            if ($key->shid != -1) {
                $trackingResult = shipmentsController::trackingShipments($key->shid);
                $containerInformation = $trackingResult[0];
                $shipmentMoves = $trackingResult[1];
                $shipmentMovesCounter = $trackingResult[2];

                // shippment Complate Progress if auto/manule
                foreach ($containerInformation as $conInfo) {
                    if ($conInfo['pauto'] == '1') {
                        //case auto on
                        foreach ($shipmentMovesCounter as $item) {
                            $shippmentComplateProgress = $item['parcantage'];
                        }
                    } else {
                        //case auto off
                        $shippmentComplateProgress = $conInfo['progress'];
                    }
                }
            } else {
                $result = -2;
            }
        }
    } else {
        $result = -1;
    }
}

function getStatusClass($statusNo)
{
    switch ($statusNo) {
        case '0':
            return 'waiting';
            break;
        case '1':
            return 'done';
            break;
    }
}

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    @include('links')
    <title>Tracking Service</title>
    <link rel="stylesheet" href="{{ asset('css/cus/tracking.css') }}">
</head>

<body>
    <section class="bg-image"
        style="background-image: url('https://source.unsplash.com/collection/2477335/1920x1080')">
        <div class="bg-cover">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="{{ asset('imgs/brand.png') }}" width="25px" height="25px">
                        Company Name
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page"
                                    href="{{ url('/' . request()->lang . '/users/login') }}">Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ url('/' . request()->lang . '/login') }}">Customer</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


            <main class="container h-100">
                <div class="row h-100 d-flex justify-content-around  main-row">
                    <div
                        class="col-lg-5 col-md-6 col-sm-12 d-flex flex-column justify-content-center order-2 order-md-1 order-sm-1">

                        @if ($result == -2)
                            <div class="ListTile not">
                                <div class="leading-Icons">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="list-content">
                                    <h4>The shipment has not been shipped yet</h4>
                                </div>
                            </div>
                        @endif

                        @if ($result == -1)
                            <!-- if theres no result  for search -->
                            <div class="ListTile notTNO">
                                <div class="leading-Icons">
                                    <i class="bi bi-question-diamond-fill"></i>
                                </div>
                                <div class="list-content">
                                    <h4>Sorry, there is no match for this number</h4>
                                </div>
                            </div>
                        @endif

                        @if (count($shipmentMoves) > 0)
                            <section>
                                <h4 class="text-light mb-4">
                                    <i class="bi bi-list-nested"></i>
                                    Shipment Movements
                                </h4>

                                <div class="mb-3">
                                    <label class="text-light mb-1">
                                        Process completion percentage {{ $shippmentComplateProgress }}%
                                    </label>

                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar"
                                            style="width: {{ $shippmentComplateProgress }}%"
                                            aria-valuenow="{{ $shippmentComplateProgress }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                                @foreach ($shipmentMoves as $item)
                                    <div class="ListTile {{ getStatusClass($item->move) }}">
                                        <div class="leading-Icons">
                                            @if ($item->move == '0')
                                                <i class="bi bi-hourglass-bottom"></i>
                                            @else
                                                <i class="bi bi-check2-all"></i>
                                            @endif
                                        </div>
                                        <div class="list-content">
                                            <h4 class="w-100">{{ $item->details }}</h4>
                                            <h5 class="w-100">
                                                <i class="bi bi-geo-alt-fill px-2"></i>
                                                {{ $item->location }}
                                            </h5>
                                            <h6 class="w-100">
                                                <i class="bi bi-calendar2-minus-fill px-2"></i>
                                                {{ $item->step_date }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach

                            </section>
                        @endif
                    </div>

                    <div class="col-lg-5 col-md-6 col-sm-12 pt-5 order-1 order-md-2 order-sm-2">
                        <div class="search-wrap mt-5">
                            <img src="{{ asset('imgs/brand.png') }}" width="100px" height="100px">
                            <h4 class="input-label mt-2">Tracking Your Shipment</h4>

                            <form action="{{ url('/' . request()->lang . '/Tracking/') }}" method="get"
                                class="w-100 d-flex justify-content-center">
                                <div class="input-wrap mt-4">
                                    <label for="" class="py-2 px-3 text-muted">#</label>
                                    <input type="text" placeholder="Enter YourTracking Number" name="TNO"
                                        value="{{ $searchBar }}" class="input-hide">
                                    <button class='Search-btn'>
                                        <i class="bi bi-search text-light"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
            </main>
        </div>
    </section>
</body>
@include('scripts')

</html>
