@php
    use App\Http\Controllers\appSetting;

    $appSettings = appSetting::getAppSettings();

    $result = count($appSettings);
    $data = $result > 0? $appSettings->first() : [];

    $closeApp = $result >0 ? $data->power : "";
    $powerOn =  $closeApp == "on"? "checked":"alt";
    $powerOff =  $closeApp == "off"? "checked":"alt";

    $closeEn = $result >0 ? $data->power_en : "";
    $closeAr = $result >0 ? $data->power_ar : "";

    $appVersion = $result >0 ? $data->version : "";

    $closeOldApp = $result >0 ? $data->old : "";
    $powerOldOn =  $closeOldApp == "on"? "checked":"alt";
    $powerOldOff =  $closeOldApp == "off"? "checked":"alt";

    $closeOldEn = $result >0 ? $data->old_en : "";
    $closeOldAr = $result >0 ? $data->old_ar : "";
    $updateLink = $result >0 ? $data->link : "";

    $legalsEn = $result >0 ? $data->legals_en : "";
    $legalsAr = $result >0 ? $data->legals_ar : "";

    $customerServices = $result >0 ? $data->cs : "";





@endphp

<form action="{{url('/updateAppSetting')}}" method="POST">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-3" dir="{{$dir}}">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <h5 class="mb-3 main-title">
                    <i class="bi bi-sliders mx-2"></i>
                    App Main Settings
                </h5>
            </div>
        </div>
    </div>
    <div class="row border-bottom pb-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Close App
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            <div class="row">
                <div class="col">
                    <label for="appOn" class=" mx-2 text-light w-100">
                        <div class=" bg-danger p-1 px-2 rounded">
                            <input type="radio" name="cloeseApp" value="off" id="appOn" {{$powerOff}}>
                            Close
                        </div>
                    </label>
                </div>
                <div class="col ">
                    <label for="appOff" class=" mx-2 text-light w-100">
                        <div class="bg-success p-1 px-2 rounded">
                            <input type="radio" name="cloeseApp" value="on" id="appOff" {{$powerOn}}>
                            Open
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Close App Resone
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm ">
                <span class="input-group-text" id="basic-addon1">En</span>
                <input type="text" name="closeEn" class="form-control form-control-sm" placeholder="Enter close message" value="{{$closeEn}}" required>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm ">
                <span class="input-group-text" id="basic-addon1">Ar</span>
                <input type="text" name="closeAr" class="form-control form-control-sm" dir="rtl" placeholder="Enter close message" value="{{$closeAr}}" required>
            </div>
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Last App Version
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            <input type="text" name="appVersion" class="form-control form-control-sm" placeholder="Enter app version" value="{{$appVersion}}" required >
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Close Old versions
        </div>

        <div class="col-md-8 col-sm-12 mb-1">
            <div class="row">
                <div class="col">
                    <label for="oldOff" class=" mx-2 text-light w-100">
                        <div class=" bg-danger p-1 px-2 rounded">
                            <input type="radio" name="closeOldVersion" value="off" id="oldOff" {{$powerOldOff}}>
                            Close
                        </div>
                    </label>
                </div>
                <div class="col ">
                    <label for="oldOn" class=" mx-2 text-light w-100">
                        <div class="bg-success p-1 px-2 rounded">
                            <input type="radio" name="closeOldVersion" value="on" id="oldOn" {{$powerOldOn}}>
                            Open
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Update Message
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm ">
                <span class="input-group-text" id="basic-addon1">En</span>
                <input type="text" name="updateMsgEn" class="form-control form-control-sm" placeholder="Enter update message" value="{{$closeOldEn}}">
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mb-1">
            <div class="input-group input-group-sm ">
                <span class="input-group-text" id="basic-addon1">Ar</span>
                <input type="text" name="updateMsgAr" class="form-control form-control-sm" dir="rtl" placeholder="Enter update message" value="{{$closeOldAr}}">
            </div>
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Update Link
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            <input type="text" class="form-control form-control-sm" name="updateLink" placeholder="Enter playstore link" value="{{$updateLink}}">
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Customer Services Phone No
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            <input type="text" class="form-control form-control-sm" name="cs" placeholder="Enter Services Phone Number" value="{{$customerServices}}">
            <small class="text-muted">Phone number @example +249XXXXXXXXX</small>
        </div>
    </div>
    <div class="row border-bottom pb-2 pt-2">
        <div class="col-md-4 col-sm-12 mb-1">
            Tearms & legals
        </div>
        <div class="col-md-8 col-sm-12 mb-1">
            <small class="lbls text-muted">Tearms & legals (En)</small>
            <textArea class="form-control" name="legalsEn">{!! str_replace('<br />', "",$legalsEn) !!}</textArea>

            <small class="lbls text-muted">Tearms & legals (Ar)</small>
            <textArea class="form-control" dir="rtl" name="legalsAr">{!! str_replace('<br />', "",$legalsAr) !!}</textArea>
        </div>
    </div>
    <div class="row pb-2 pt-2 mt-3">
        <div class="col-md-12 col-sm-12 mb-1 text-end">
            @csrf

            <button class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</form>
