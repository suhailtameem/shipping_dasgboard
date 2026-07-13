@php
if (!Session::has('user')) {
    return redirect()
        ->to(request()->lang . '/users/login')
        ->send();
}
$current = request()->segment(count(request()->segments()));
@endphp

<nav class="navbar navbar-expand-lg navbar-light px-4 fixed-top main-nav cc">
    <div class="container-fluid">
        @if ($current != 'dashboard')
            <a class="navbar-brand" href="{{ Request::server('HTTP_REFERER') }}">
                <div class="backArrow">
                    <i class="bi bi-arrow-left"></i>
                </div>
            </a>
        @endif
        @if (Session::has('status'))
            @if (Session::get('stype') == 'success')
                <div class="alert py-1 mb-0 alert-success alert-bar">
                    <i class="bi bi-check2-all me-2"></i>
                    {{ Session::get('status') }}
                </div>
            @else
                <div class="alert py-1 mb-0 alert-danger alert-bar">
                    <i class="bi bi-exclamation-diamond-fill me-2"></i>
                    {{ Session::get('status') }}
                </div>
            @endif
        @endif
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                        <div data-bs-toggle="offcanvas" data-bs-target="#profileCanvas" aria-controls="profileCanvas"
                            class="profileMenu">
                            <img src="{{ asset(Session::get('user')->img) }}" alt="" width="40px"
                                height="40px">
                            <div class="ico-wrap">
                                <h4 class="d-inline">
                                    <i class="bi bi-list"></i>
                                </h4>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<aside class="aside">
    <img src="{{ asset('imgs/brand.png') }}" width="50px" height="50px" class="aside-brand">

    <ul class="aside-list">
        <li>
            <a href="{{ url(request()->lang . '/dashboard') }}">
                <div class="aitems">
                    <i class="bi bi-speedometer2"></i>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ url(request()->lang . '/request-list') }}" class="links">
                <div class="aitems">
                    <i class="bi bi-inbox-fill"></i>
                </div>
            </a>
        </li>


        <li>
            <a href="{{ url(request()->lang . '/air-freight') }}" class="links">
                <div class="aitems">
                    <i class="bi bi-send-fill"></i>
                </div>
            </a>
        </li>
        <li>
            <a href="{{ url(request()->lang . '/sea-freight') }}" class="links">
                <div class="aitems">
                    <i class="las la-ship"></i>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ url(request()->lang . '/land-transport') }}" class="links">
                <div class="aitems">
                    <i class="las la-shipping-fast"></i>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ url(request()->lang . '/rates') }}" class="links">
                <div class="aitems">
                    <i class="bi bi-currency-exchange"></i>
                </div>
            </a>
        </li>

        <li>
            <a href="{{ url(request()->lang . '/Mobile') }}" class="links">
                <div class="aitems">
                    <i class="bi bi-phone-fill"></i>
                </div>
            </a>
        </li>
    </ul>

    <label for="logoutSubmit" class="logout-btn">
        <div class="aitems">
            <i class="bi bi-box-arrow-left"></i>
        </div>
    </label>
    <form action="{{ url('/userLogout') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" value="{{ request()->lang }}" name="lang">
        <button type="submit" id="logoutSubmit">Logout</button>
    </form>
</aside>

{{-- User Profile offcanvas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="profileCanvas" aria-labelledby="profileCanvasLabel">
    <div class="offcanvas-header d-flex flex-column justify-content-center">
        <img src="{{ asset(Session::get('user')->img) }}" class="profileImg">
        <h5 class="userName">{{ Session::get('user')->name }}</h5>
    </div>
    <div class="offcanvas-body pb-5">
        <form action="{{ url('/proImg') }}" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input class="form-control form-control-theme" name="uimg" id="formFileSm" type="file">
                @csrf
                <input type="hidden" name="uid" value="{{ Session::get('user')->id }}">
            </div>
            <button class="btn btn-sm btn-primary w-100 mt-2"> Change Profile Picture</button>
        </form>

        <form action="{{ url('/basicInfo') }}" method="POST" class="mt-5">
            <h4 class="sec-title">Basic Information</h4>
            <div class="form-floating mb-3">
                <input type="text" name="userName" class="form-control"
                    value="{{ Session::get('user')->name }}" placeholder="Enter User Name">
                <label>User Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="jobTitle" class="form-control"
                    value="{{ Session::get('user')->title }}" placeholder="Software Devoloper">
                <label>Job Title</label>
            </div>

            @csrf
            <input type="hidden" name="uid" value="{{ Session::get('user')->id }}">
            <button class="btn btn-sm btn-primary w-100 mt-2">Update Basic Information</button>
        </form>


        <form action="{{ url('/updatePass') }}" method="POST" class="mt-5">
            <h4 class="sec-title">Change Password</h4>

            <div class="form-floating mb-3">
                <input type="password" name="passCode" class="form-control" placeholder="*******" required>
                <label>Old Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="newPassCode" class="form-control" placeholder="*******" required>
                <label>New Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="confPassCode" class="form-control" placeholder="*******" required>
                <label>Confirm Password</label>
            </div>

            @csrf
            <input type="hidden" name="uid" value="{{ Session::get('user')->id }}">
            <button class="btn btn-sm btn-primary w-100 mt-2">Change Password</button>
        </form>

    </div>
</div>
