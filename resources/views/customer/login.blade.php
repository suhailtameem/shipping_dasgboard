<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>Registration</title>

    <link rel="stylesheet" href="{{asset('css/usersLogin.css')}}">
    <link rel="stylesheet" href="{{asset('css/cus/signup.css')}}">
</head>
<body class="pt-0" style="background-image: url('{{asset('imgs/login_bg.jpg')}}');">
    <div class="bodyCover" >
        <main class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-sm-12  mb-2">
                    <div class="login-div">
                        <div class="row">
                            <div class="col">


                                <div class="d-flex flex-column align-items-center mb-5">
                                    <img src="{{asset('imgs/brand.png')}}" width="120px" >
                                    <h4 class="co-title mt-4">
                                        {{ __('lang.CustomersLogin') }}    
                                    </h4>     
                                    <small class="co-subtitle">
                                        {{ __('lang.customerLoginSub') }}
                                    </small>
                                </div>

                                <div class="login-content ">
                                    <div class="leading">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <form action="{{url('/cusLogin')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="lang" value="{{request()->lang}}">
                                        <input type="hidden"name="app" value="web">
                                        <div class="form-content">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text" >
                                                    <i class="bi bi-envelope-fill"></i>
                                                </span>
                                                <input type="email" name="email" class="form-control" placeholder="Enter your email" >
                                            </div>
                                            <div class="input-group ">
                                                <span class="input-group-text" >
                                                    <i class="bi bi-shield-lock-fill"></i>
                                                </span>
                                                <input type="password" name="password" class="form-control" placeholder="Enter your password" >
                                                <button class="btn btn-success">
                                                    <i class="bi bi-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                @if(Session::has('status'))
                                    @if(Session::get('stype') == "success")
                                        <div class="alert alert-sm mt-3 passAlert passAlert-success">
                                            <i class="bi bi-check2-all"></i>
                                            {{Session::get('status')}}
                                        </div>
                                    @else
                                        <div class="alert alert-sm mt-3 passAlert">
                                            <i class="bi bi-exclamation-diamond-fill px-2"></i>
                                            {{Session::get('status')}}
                                        </div>
                                    @endif
                                @endif

                                <div class="mt-4 mb-5 text-center">
                                    <span class="text-muted">
                                        You don't have an account?
                                    </span>
                                    <a href="{{url('/'.request()->lang.'/signup')}}" class="btn btn-sm text-light links">
                                        Signup
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5 mb-5">
                <div class="col text-center">
                    <a href="https://www.tameem.sd/" target="_blank">
                        <img src="{{asset('imgs/tm_short_light.png')}}" height="40px">
                    </a>
                </div>
            </div>
        </main>
    </div>
    @include('scripts')
</body>





</html>
