@php
    use App\Http\Controllers\shipmentsController;

    $lang = request()->lang;
    $dir = $lang == "Ar"? "rtl" : "ltr";
    $CenterArText = $lang == "Ar"? "text-center" : " ";

    $DID = request()->did;
    $Addresses= shipmentsController::getAddressesBy($DID);
@endphp
<!DOCTYPE html>
<html lang="{{$lang}}">
<head>
    @include('links')
    <title>
        Addresses
    </title>
</head>
<style>
    .sec{
        background: #fff;
        border-radius: 10px;
        padding: 30px 20px;
    }
    .lbls{
        font-size: 14px;
        color: #646464;
    }
</style>
<body>
    @include('nav-aside')

    <main class="main-stage">
        <div class="container ">
            <div class="row border-bottom pb-1 mb-3" dir="{{$dir}}">
                <div class="col">
                    <h4 class="main-title">
                        <i class="bi bi-geo-alt-fill mx-2"></i>
                        @lang('lang.Addresses')
                    </h4>
                </div>
                <div class="col"></div>
            </div>

            <div class="row">
                <div class="col" dir="{{$dir}}" >
                    <form action="{{url('/addDestAddress')}}" method="POST">
                        <section class="sec">
                            <div class="row">
                                <div class="col">
                                    <label class="lbls">
                                        @lang('lang.AddEn')
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="en" placeholder="@lang('lang.EnterAddrEn')">
                                </div>
                                <div class="col">
                                    <label class="lbls">
                                        @lang('lang.AddressAr')
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="ar" placeholder="@lang('lang.EnterAddAr')">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="lbls">
                                        @lang('lang.PhoneNo1')
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="phone1" placeholder="@lang('lang.EnterPhoneNo')">
                                </div>
                                <div class="col">
                                    <label class="lbls">
                                        @lang('lang.PhoneNo2')
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="phone2" placeholder="@lang('lang.EnterPhoneNo')">
                                </div>
                            </div>
                            <div class="row mt-3 pt-2">
                                <div class="col text-end">
                                    @csrf
                                    <input type="hidden" name="did" value="{{$DID}}">
                                    <button class="btn btn-success">
                                        <i class="bi bi-plus-circle-dotted mx-1"></i>
                                        @lang('lang.AddAddress')
                                    </button>
                                </div>
                            </div>
                        </section>
                    </form>

                    <table class="table table-hover table-striped mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    @lang('lang.AddEn')
                                </th>
                                <th>
                                    @lang('lang.AddressAr')
                                </th>
                                <th>
                                    @lang('lang.PhoneNo1')
                                </th>
                                <th>
                                    @lang('lang.PhoneNo2')
                                </th>
                                <th>
                                    @lang('lang.Edit')
                                </th>
                                <th>
                                    @lang('lang.Delete')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1 ;
                            @endphp
                            @foreach ($Addresses as $item)
                                <form formaction="{{url('/updateDestAddress')}}" method="POST">
                                    <tr>
                                        <th>
                                            {{$counter++}}
                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            @csrf
                                        </th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="en" value="{{$item->en}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="ar" value="{{$item->ar}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="phone1" value="{{$item->phone1}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="phone2" value="{{$item->phone2}}">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" formaction="{{url('/deleteAddress/'.$item->id)}}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" formaction="{{url('/updateDestAddress')}}">
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
    </main>
</body>
@include('scripts')
</html>
