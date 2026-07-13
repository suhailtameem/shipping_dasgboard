@php
    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl" : "ltr";
    $CenterArText = $lang == "Ar"? "text-center" : " ";
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @include('links')
    <title>
        @lang('lang.reqContentTap')
    </title>
    <link rel="stylesheet" href="{{asset('css/shipping.css')}}">
</head>
<body>
    @include('nav-aside')
    <main class="main-stage">
        @include('shipping.requests')
    </main>
</body>
@include('scripts')
<script src="{{asset('js/sh-request.js')}}"></script>
</html>
