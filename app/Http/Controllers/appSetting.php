<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\appSetting;

use Illuminate\Http\Request;
use App\Models\appSettings;
use Illuminate\Support\Facades\Session;


/*
    "power",
    "power_en",
    "power_ar",
    "version",
    "old",
    "old_en",
    "old_ar",
    "link",
    "legals_en",
    "legals_ar",
    "wsid"



    cloeseApp
    closeEn
    closeAr
    appVersion
    closeOldVersion
    updateMsgEn
    updateMsgAr
    updateLink
    legalsEn
    legalsAr
*/

class appSetting extends Controller{

    public static function getAppSettings(){
        return appSettings::get();
    }

    public function updateAppSettings(Request $request){
        $update =  appSettings::where('id', '1')->update([
            "power"=>$request->input('cloeseApp'),
            "power_en"=>$request->input('closeEn'),
            "power_ar"=>$request->input('closeAr'),
            "version"=>$request->input('appVersion'),
            "old"=>$request->input('closeOldVersion'),
            "old_en"=>$request->input('updateMsgEn'),
            "old_ar"=>$request->input('updateMsgAr'),
            "link"=>$request->input('updateLink'),
            "legals_en"=>nl2br($request->input('legalsEn')),
            "legals_ar"=>nl2br($request->input('legalsAr')),
            "cs"=>$request->input('cs'),
        ]);

        if($update){
            Session::flash('status', "App settings updated.");
            Session::flash('stype', "success");
        }else{
            Session::flash('status', "App settings not updated.");
            Session::flash('stype', "danger");
        }

        return redirect()->back();
    }
}
