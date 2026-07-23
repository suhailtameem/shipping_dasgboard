@php
    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl" :"ltr";


    use App\Models\company;

    $company = company::first();
    $coLogo        = $company && $company->logo        ? asset( $company->logo) : asset('imgs/brand.png');
    $coName        = $company && ($lang == 'Ar' ? $company->name_ar        : $company->name_en)
                        ? ($lang == 'Ar' ? $company->name_ar        : $company->name_en)
                        : __('lang.CoTitle');
    $coDescription = $company && ($lang == 'Ar' ? $company->description_ar : $company->description_en)
                        ? ($lang == 'Ar' ? $company->description_ar : $company->description_en)
                        : __('lang.CoSubtitle');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>{{__('lang.loginTapTitle')}}</title>
    <link rel="stylesheet" href="{{asset('css/usersLogin.css')}}">
</head>
<body style="background-image: url({{asset('imgs/login-page.jpg')}});">
    <div class="cover">
        <main class="container d-flex flex-column align-items-center justify-content-center h-100">
            <div class="row w-100 d-flex flex-row justify-content-center">
                <div class="col-md-6 col-sm-12 d-flex justify-content-center mb-3">
                    <div class="Company">
                        <img src="{{ $coLogo }}" class="brand rounded-lg" width="100px" height="100px">
                        <h4 class="co-title">{{ $coName }}</h4>
                        <small class="co-subtitle">
                            {{ $coDescription }}
                        </small>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 d-flex flex-column justify-content-center align-items-center border-start border-css col-show">
                    <div class="login-content">
                        <div class="leading">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <form action="{{url('/UserLogin')}}" method="POST">
                            @csrf
                            <input type="hidden" name="lang" value="{{request()->lang}}">
                            <div class="form-content" >
                                <div class="input-group mb-2">
                                    <span class="input-group-text" >
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" dir="{{$dir}}" placeholder="{{__('lang.emailHolder')}}">
                                </div>
                                <div class="input-group ">
                                    <span class="input-group-text" >
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" dir="{{$dir}}" placeholder="{{__('lang.passwordHolder')}}">
                                    <button class="btn btn-success">
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(Session::has('status'))
                        <div class="alert alert-sm mt-3 passAlert">
                            <i class="bi bi-exclamation-diamond-fill px-2"></i>
                            {{Session::get('status')}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col text-center">
                    @php
                        $lang = request()->lang == "Ar" ? "En": "Ar";
                    @endphp
                    <a href="{{url($lang.'/users/login')}}" class="langSwitcher">
                        <span>
                            <i class="bi bi-translate"></i>
                        </span>

                    </a>
                </div>
            </div>
        </main>
        <div class="foot rowUp">
            <section class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-2 text-center">
                        <a href="{{url('https://www.tameem.sd/')}}" target="_blank">
                            <img src="{{asset('imgs/tm_short_light.png')}}" height="40px">
                        </a>
                        <p class="copyright">
                            Copyright ©2022 All Rights Reserved your-company: Powered By Tameem Business
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
@include('scripts')
</html>
