@php
    if(!Session::has('customer')) return redirect()->to(request()->lang.'/login')->send();
    $current = request()->segment(count(request()->segments()));

    // Helper functions
    function match_list($a,$b){
        if($a == $b) return "selected";
        else return "";
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-light px-4 fixed-top main-nav cc">
    <div class="container-fluid">

        @if(Session::has('status'))
            @if(Session::get('stype') == "success")
                <div class="alert py-1 mb-0 alert-success alert-bar">
                    <i class="bi bi-check2-all mx-2"></i>
                    {{Session::get('status')}}
                </div>
            @else
            <div class="alert py-1 mb-0 alert-danger alert-bar">
                <i class="bi bi-exclamation-diamond-fill mx-2"></i>
                {{Session::get('status')}}
            </div>
            @endif
        @endif
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page">
                        <div
                            data-bs-toggle="offcanvas"
                            data-bs-target="#profileCanvas"
                            aria-controls="profileCanvas"
                            class="profileMenu">
                            <img
                                src="{{asset('imgs/users/placeholder.png')}}"
                                alt=""
                                width="40px"
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
    <img src="{{asset('imgs/brand.png')}}" width="50px" height="50px" class="aside-brand" >

    <ul class="aside-list">
        <li>
            <a href="{{url(request()->lang.'/home')}}">
                <div class="aitems">
                    <i class="bi bi-house"></i>
                </div>
            </a>
        </li>
        <li>
            <a href="{{url(request()->lang.'/air-freight')}}" class="links">
                <div class="aitems">
                    <i class="bi bi-box-seam"></i>
                </div>
            </a>
        </li>
        <li>
            <a href="{{url(request()->lang.'/sea-freight')}}" class="links">
                <div class="aitems">
                    <i class="bi bi-geo-alt"></i>
                </div>
            </a>
        </li>

        <li>
            <a href="{{url(request()->lang.'/land-transport')}}" class="links">
                <div class="aitems">
                    <i class="bi bi-info-circle"></i>
                </div>
            </a>
        </li>



    </ul>

    <label for="logoutSubmit" class="logout-btn">
        <div class="aitems">
            <i class="bi bi-box-arrow-left"></i>
        </div>
    </label>
    <form action="{{url('/logout')}}" method="POST" class="d-none">
        @csrf
        <input type="hidden" value="{{request()->lang}}" name="lang">
        <button type="submit" id="logoutSubmit">Logout</button>
    </form>
</aside>

{{-- User Profile offcanvas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="profileCanvas" aria-labelledby="profileCanvasLabel">
    <div class="offcanvas-header d-flex flex-column justify-content-center">
        <img src="{{asset('imgs/users/placeholder.png')}}" class="profileImg">
        <h5 class="userName">{{Session::get('customer')->first.' '.Session::get('customer')->last}}</h5>
    </div>
    <div class="offcanvas-body pb-5">
        @if(false)
            <form action="{{url('/proImg')}}" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <input class="form-control form-control-theme" name="uimg" id="formFileSm" type="file">
                    @csrf
                    <input type="hidden" name="uid" value="{{Session::get('customer')->id}}">
                </div>
                <button class="btn btn-sm btn-primary w-100 mt-2"> Change Profile Picture</button>
            </form>
        @endif

        <form action="{{url('/updateBasic')}}" method="POST" class="">
            <h4 class="sec-title">Basic Information</h4>
            <div class="form-floating mb-3">
                <input type="text" name="fname" class="form-control" value="{{Session::get('customer')->first}}" placeholder="Enter your first name">
                <label>First Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="lname" class="form-control" value="{{Session::get('customer')->last}}" placeholder="Enter your last name">
                <label>Last Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="full" class="form-control" value="{{Session::get('customer')->full}}" placeholder="Enter your full name">
                <label>Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="phone" class="form-control" value="{{Session::get('customer')->phone}}" placeholder="Enter phone number">
                <label>Phone Number</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="phone2" class="form-control" value="{{Session::get('customer')->phone2}}" placeholder="Enter other phone number">
                <label>Other Phone Number</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="email" class="form-control" value="{{Session::get('customer')->email}}" placeholder="Enter your email address">
                <label>Email Address</label>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label  class="form-label txt-lbls">Country</label>
                    <select class="form-select" name="country">
                        <option value="1" {{ match_list('1',Session::get('customer')->country)}}>Sudan</option>
                        <option value="2" {{ match_list('2',Session::get('customer')->country)}}>UAE</option>
                    </select>
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="hidden" name="app" value="web">
                <input type="text" name="addr" class="form-control" value="{{Session::get('customer')->address}}" placeholder="Software Devoloper">
                <label>Address</label>
            </div>

            @csrf
            <input type="hidden" name="cid" value="{{Session::get('customer')->id}}">
            <button class="btn btn-sm btn-primary w-100 mt-2">Update Basic Information</button>
        </form>


        <form action="{{url('/updatePassword')}}" method="POST" class="mt-5">
            <h4 class="sec-title">Change Password</h4>

            <div class="form-floating mb-3">
                <input type="password" name="pass" class="form-control" placeholder="*******" required>
                <label >Old Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="newPass" class="form-control" placeholder="*******" required>
                <label >New Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="newPass_confirmation" class="form-control" placeholder="*******" required>
                <label >Confirm Password</label>
            </div>

            @csrf
            <input type="hidden" name="cid" value="{{Session::get('customer')->id}}">
            <input type="hidden" name="app" value="web">
            <button class="btn btn-sm btn-primary w-100 mt-2">Change Password</button>
        </form>

    </div>
</div>
