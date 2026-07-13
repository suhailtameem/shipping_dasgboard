<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\settingsController as Settings;

use Illuminate\Http\Request;
use App\Models\{countries,currencies,shippingRates};
use Illuminate\Support\Facades\Session;
use Redirect;


class settingsController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function indexCurrencies(){
        $indexs = currencies::orderBy('id','desc')->get();
        return $indexs;
    }

    public static function indexShippingRates(){
        $indexs = shippingRates::
        orderBy('shtype','desc')
        ->orderBy('weight_from', 'ASC')
        ->get();
        return $indexs;
    }

    public static function indexShippingRatesBy($shippingType,$countryFrom,$countryTo){
        $indexs = shippingRates::
        orderBy('shtype','desc')
        ->orderBy('weight_from', 'ASC')
        ->where('shtype',$shippingType)
        ->where('from',$countryFrom)
        ->where('to',$countryTo)
        ->get();
        return $indexs;
    }

    public static function indexCountaries(){
        $indexs = countries::orderBy('id','desc')->get();
        return $indexs;
    }


    public function storeCurrancy(Request $request){
        $store = currencies::create([
            "currency"=>$request->input("curr"),
            "currency_ar"=>$request->input("curr_ar"),
            "usdRate"=>$request->input("usdRate"),
        ]);

        if($store){
            Session::put('status','Currency added successfully');
            Session::put('stype','success');
            
        }else{
            Session::put('status','Fail to add Currency');
            Session::put('stype','danger');
        }
        
        return redirect()->back();
    }

    public function storeRates(Request $request){
        $store  =  shippingRates::create([
            "shtype"=>$request->input('shtype'),
            "from"=>$request->input('countryFrom'),
            "to"=>$request->input('countryTo'),
            "weight_from"=>$request->input('wfrom'),
            "Weight_to"=>$request->input('wto'),
            "unit"=>$request->input('unit'),
            "price"=>$request->input('price'),
            "currency"=>'USD',
        ]);

        if($store){
            Session::put('status','Rate added successfully');
            Session::put('stype','success');

        }else{
            Session::put('status','Fail to add shipping rate');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    public function storeCountries(Request $request){
        $currency = currencies::create([
            "currency" => $request->input("curr"),
            "currency_ar" => $request->input("curr_ar"),
            "usdRate" => $request->input("usdRate", 1.0000),
        ]);

        $store =  countries::create([
            "name"=>$request->input('cname'),
            "arabic"=>$request->input('caname'),
            "status"=>$request->input('cstatus'),
            "currency_id" => $currency->id,
        ]);

        if($store){
            Session::put('status','Country and Currency added successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to add this country');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function showCurrancy($id){
        $show = currencies::whereId($id)->get();
        return $show;
    }

    public static function showCurrancies(){
        $show = currencies::get();
        return $show;
    }


    public static function showCountry($id){
        $show = countries::whereId($id)->get();
        return $show;
    }

 
    public function updateCurrancy(Request $request, $id){
        $update = currencies::whereId($id)->update([
            "currency"=>$request->input("curr"),
            "currency_ar"=>$request->input("curr_ar"),
            "usdRate"=>$request->input("usdRate"),
        ]);

        if($update){
            Session::put('status','Currency updated successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to update Currency');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    public function updateRate(Request $request, $id){
        $user = Session::get('user')->id;
        $update  =  shippingRates::whereId($id)->update([
            "shtype"=>$request->input('shtype'),
            "from"=>$request->input('countryFrom'),
            "to"=>$request->input('countryTo'),
            "weight_from"=>$request->input('wfrom'),
            "Weight_to"=>$request->input('wto'),
            "unit"=>$request->input('unit'),
            "price"=>$request->input('price'),
            "currency"=>'USD',
            "updated_by"=>$user,
        ]);

        if($update){
            Session::put('status','Rate updates successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to update shipping rate');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    public function updateCountries(Request $request, $id){
        $country = countries::findOrFail($id);
        
        // Handle Currency logic
        if ($country->currency_id) {
            // Update existing currency
            currencies::whereId($country->currency_id)->update([
                "currency" => $request->input("curr"),
                "currency_ar" => $request->input("curr_ar"),
                "usdRate" => $request->input("usdRate"),
            ]);
        } else {
            // Create new currency and link it
            $currency = currencies::create([
                "currency" => $request->input("curr"),
                "currency_ar" => $request->input("curr_ar"),
                "usdRate" => $request->input("usdRate"),
            ]);
            $country->currency_id = $currency->id;
        }

        // Update country data
        $update = $country->update([
            "name"=>$request->input('cname'),
            "arabic"=>$request->input('caname'),
            "status"=>$request->input('cstatus'),
            "currency_id" => $country->currency_id, // Ensure ID is saved if newly created
        ]);

        if($update){
            Session::put('status','Country and Currency updated successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to update this country');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCurrency($id){
        $destroy = currencies::destroy($id);
        if($destroy){
            Session::put('status','Currency deleted successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to delete Currency');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    public function destroyRate($id){
        $destroy = shippingRates::destroy($id);
        if($destroy){
            Session::put('status','Rate deleted successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to delete this rate');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }

    public function destroyCountry($id){
        $country = countries::find($id);
        if($country){
            if($country->currency_id){
                currencies::destroy($country->currency_id);
            }
            $destroy = $country->delete();
            Session::put('status','Country and its Currency deleted successfully');
            Session::put('stype','success');
        }else{
            Session::put('status','Fail to find this country');
            Session::put('stype','danger');
        }

        return redirect()->back();
    }
}
