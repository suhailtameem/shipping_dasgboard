<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>Home</title>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
</head>
<body>
    @include('customer.nav-frame')
    <main class="main-stage">
        <div class="container-fluid">
            <div class="col-lg-9 col-md-8 col-sm-12 mb-3">

                <div class="row border-top border-bottom">
                    <div class="col-md-4 mb-1">
                        <div class="list-tile">
                            <div class="tile-leading">
                                <i class="bi bi-house"></i>
                            </div>
                            <div class="tile-content">
                                <h4 class="list-title">Home</h4>
                                <small class="list-subtitle"></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-1">
                        <div class="list-tile">
                            <div class="tile-leading" id="MoNi-icon">
                                <i class="bi bi-clouds"></i>
                            </div>
                            <div class="tile-content">
                                <h4 class="list-title" id="MoNi">Good Morning</h4>
                                <small class="list-subtitle">
                                    Mr. Suhail Tameem
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
                                <h4 class="list-title">Today is</h4>
                                <small class="list-subtitle" id="today">
                                    20 Setemper 2021
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-3">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <a href="{{url(request()->lang.'/air-freight/')}}" class="links">
                            <div class="menu-item blue-item">
                                <div class="ihead">
                                    <div class="item-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="item-indecator">
                                        20/SH
                                    </div>
                                </div>
                                <h4 class="item-title">
                                    New Shipments
                                </h4>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <a href="{{url(request()->lang.'/air-freight/')}}" class="links">
                            <div class="menu-item blue-item">
                                <div class="ihead">
                                    <div class="item-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="item-indecator">
                                        A/M
                                    </div>
                                </div>
                                <h4 class="item-title">
                                    Shipment Tracking
                                </h4>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row border-top border-bottom">
                    <div class="col-md-4 mb-1">
                        <div class="list-tile">
                            <div class="tile-leading">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="tile-content">
                                <h4 class="list-title">Latest Shipments</h4>
                                <small class="list-subtitle"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('scripts')
</body>
</html>
