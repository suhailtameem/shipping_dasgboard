<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\customerController as Customers;
use App\Http\Controllers\listsControllrt as Lists;
use App\Http\Controllers\shipmentsController;
use Illuminate\Http\Request;
use App\Models\{customers, destAddress, shDestinations};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/*

'first',
'last',
'full',
'email',
'phone',
'phone2',
'country',
'address',
'password',
'type',
'ws',
'last_login',
'use',

/ Form inputs /
fname
lname
full
email
phone
phone2
country
addr
pass
password_confirmation
app
lang
workstation
    return response()->json($response);
*/
class customerController extends Controller
{
    public function signup(Request $request)
    {
        $sstatus = 'Account created successfully, login to verify account';
        $dstatus = 'Account creation failed, try again later';
        $lang = $request->input('lang');
        $App = $request->input('app');

        $validated = $request->validate([
            'legals' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|unique:customers',
            'phone' => 'required|unique:customers',
            'pass' => 'required|string|confirmed',
        ]);

        $password = md5($request->pass);

        // Handle File upload for id_proff_image if present (assuming base64 or file upload logic might be needed later, 
        // but for now just accepting the input if it's a string/path)
        $idProffImage = $request->input('id_proff_image');

        $create = customers::create([
            'first' => $request->input('fname'),
            'last' => $request->input('lname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'phone2' => $request->input('phone2'),
            'country' => $request->input('country'),
            'address' => $request->input('addr'),
            'location' => $request->input('location'),
            'id_proff_image' => $idProffImage,
            'password' => $password,
        ]);

        if ($create) {
            if ($request->input('app') == 'web') {
                $this->Response($sstatus, 'success', $App);
                return redirect()
                    ->to('/' . $lang . '/login')
                    ->send();
            } elseif ($request->input('app') == 'android') {
                return $this->Response($sstatus, 'success', $App);
            } else {
                return 0;
            }
        } else {
            if ($request->input('app') == 'web') {
                $this->Response($dstatus, 'danger', $App);
                return redirect()->back();
            } elseif ($request->input('app') == 'android') {
                return $this->Response($dstatus, 'danger', $App);
            } else {
                return 0;
            }
        }
    }


    public function apiSignup(Request $request){
        $sstatus = 'Account created successfully, login to verify account';
        $dstatus = 'Account creation failed, try again later';
        $lang = $request->input('lang');
        $App = $request->input('app');

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            if ($request->filled('email') && customers::where('email', $request->input('email'))->exists()) {
                return response()->json([
                    'scode' => "2",
                    'status' => "Email is already exisit",
                    'stype' => "danger",
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'scode' => "0",
                'status' => "Validation failed: " . implode(', ', $validator->errors()->all()),
                'stype' => "danger",
                'data' => [],
            ], 200);
        }

        if (customers::where('email', $request->input('email'))->exists()) {
            return response()->json([
                'scode' => "2",
                'status' => "Email is already exisit",
                'stype' => "danger",
                'data' => [],
            ], 200);
        }

        $password = md5($request->password);
        $create = customers::create([
            'first' => $request->input('firstName'),
            'last' => $request->input('lastName'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'location' => $request->input('location'),
            'password' => $password,
            'lang' => "1",
            'type' => $request->input('getway'),
            'use' => $App,
        ]);

        if ($create) {
            return response()->json([
                'scode' => "1",
                'status' => $sstatus,
                'stype' => "success",
                'data' => [],
            ]);
        } else {
            return response()->json([
                'scode' => "0",
                'status' => $dstatus,
                'stype' => "danger",
                'data' => [],
            ]);
        }
    }

    public function login(Request $request){
        $lang = $request->lang;
        $App = $request->app;
        $token = $request->token;
        $lastLogin = date('Y-m-d H:i:s');
        $logiF = 'Login failed Email/Password Not Correct.';

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($App == 'web') {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            return response()->json([
                'status' => 'Email and password are required.',
                'stype' => 'danger',
                'data' => [],
            ]);
        }

        //Check the email if exsist
        $user = customers::where('email', $request->email)
            ->where('password', md5($request->password))
            ->get();

        //Check the password
        if (count($user) > 0) {
            $user = $user->first();
            //====================  update user action =====================
            $customer = customers::whereId($user->id)->update([
                'lang' => $lang,
                'use' => $App,
                'token' => $token,
                'last_login' => $lastLogin,
            ]);

            //========================== response ==========================

            if ($App == 'web') {
                $this->Response('success', 'success', $App);
                $request->session()->put('customer', $user);
                return redirect()
                    ->to(url($request->lang . '/home'))
                    ->send();
            } else {
                //Get system list for mobile app
                $sysList = Lists::getLists();
                $user->sysLists = $sysList;

                //Gey shipments destnations
                $user->AirDest = shipmentsController::getDestinations(1);
                $user->SeaDest = shipmentsController::getDestinations(2);
                $user->LandDest = shipmentsController::getDestinations(3);

                //get destnation addresses and map fields to match the mobile app specification
                $destAddresses = destAddress::get();
                $allDests = shDestinations::get()->keyBy('id');
                foreach ($destAddresses as $addr) {
                    $addr->name = $addr->en ?? '';
                    $addr->address = $addr->en ?? '';
                    $destObj = $allDests->get($addr->did);
                    $addr->country = $destObj ? $destObj->destinations : '';
                }
                $user->addresses = $destAddresses;

                return $this->Response('success', 'success', $App, $user);
            }
        } else {
            if ($App == 'web') {
                $this->Response($logiF, 'danger', $App);
                return redirect()->back();
            }
            return $this->Response($logiF, 'danger', $App);
        }
    }

    public static function getCustomers()
    {
        $customers = customers::orderBy('created_at', 'desc')
            ->whereNotNull('token')
            ->get();
        return $customers;
    }

    public static function getAllCustomers()
    {
        $customers = customers::orderBy('created_at', 'desc')->get();
        return $customers;
    }


    public static function getCustomerBy($id)
    {
        $customers = customers::where('id', $id)
            ->whereNotNull('token')
            ->get();
        return $customers;
    }



    public function changePassword(Request $request)
    {
        $App = $request->app;
        $succ = 'Password changed successfully';
        $failed = 'Failed to change password';
        $oldError = 'Your old password not correct...';

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'cid' => 'required|exists:customers,id',
            'pass' => 'required|string',
            'newPass' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($App == 'web') {
                Session::flash('status', 'Validation failed: ' . implode(', ', $validator->errors()->all()));
                Session::flash('stype', 'danger');
                return redirect()->back();
            }
            return response()->json([
                'status' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                'stype' => 'danger',
                'data' => [],
            ]);
        }

        $user = customers::find($request->cid);
        if (!$user || md5($request->pass) !== $user->password) {
            if ($App == 'web') {
                $this->Response($oldError, 'danger', $App);
                return redirect()->back();
            }
            return $this->Response($oldError, 'danger', $App);
        }

        $update = customers::where('id', $request->cid)->update([
            'password' => md5($request->newPass),
        ]);

        if ($update) {
            if ($App == 'web') {
                $this->Response($succ, 'success', $App);
                return redirect()->back();
            }
            return $this->Response($succ, 'success', $App);
        } else {
            if ($App == 'web') {
                $this->Response($failed, 'danger', $App);
                return redirect()->back();
            }
            return $this->Response($failed, 'danger', $App);
        }
    }

    public function updateBasic(Request $request)
    {
        $App = $request->app;
        $succ = 'Your basic information updated successfully';
        $faile = 'Failed update information';

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'cid' => 'required|exists:customers,id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $request->cid,
            'phone' => 'required|string|max:255|unique:customers,phone,' . $request->cid,
        ]);

        if ($validator->fails()) {
            if ($App == 'web') {
                Session::flash('status', 'Validation failed: ' . implode(', ', $validator->errors()->all()));
                Session::flash('stype', 'danger');
                return redirect()->back()->withInput();
            }
            return response()->json([
                'status' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                'stype' => 'danger',
                'data' => [],
            ]);
        }

        $update = customers::where('id', $request->cid)->update([
            'first' => $request->input('fname'),
            'last' => $request->input('lname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'phone2' => $request->input('phone2'),
            'country' => $request->input('country'),
            'address' => $request->input('addr'),
            'location' => $request->input('location'),
        ]);

        //upddate info in session
        $customer = customers::whereId($request->cid)
            ->get()
            ->first();
        Session::forget('customer');
        Session::put('customer', $customer);

        if ($update) {
            if ($App == 'web') {
                $this->Response($succ, 'success', $App);
                return redirect()->back();
            } else {
                return $this->Response($succ, 'success', $App);
            }
        } else {
            if ($App == 'web') {
                $this->Response($faile, 'danger', $App);
                return redirect()->back();
            } else {
                return $this->Response($faile, 'danger', $App);
            }
        }
    }

    public function adminUpdateCustomer(Request $request) 
    {
        $succ = 'Customer information updated successfully';
        $fail = 'Failed to update customer information';
        
        $validated = $request->validate([
            'cid' => 'required|exists:customers,id',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        try {
            $update = customers::where('id', $request->cid)->update([
                'first' => $request->input('fname'),
                'last' => $request->input('lname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'country' => $request->input('country'),
                'address' => $request->input('addr'),
                'location' => $request->input('location'),
            ]);

            if ($update) {
                // Do NOT update session as this is likely an admin action
                $this->Response($succ, 'success', 'web');
                return redirect()->back();
            } else {
                $this->Response($fail, 'danger', 'web');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->Response($fail . ': ' . $e->getMessage(), 'danger', 'web');
            return redirect()->back();
        }
    }

    public function Response($status, $stype, $App, $data = [])
    {
        if ($App == 'web') {
            Session::flash('status', $status);
            Session::flash('stype', $stype);
        } else {
            return response()->json([
                'status' => $status,
                'stype' => $stype,
                'data' => $data,
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('customer');
        return redirect($request->lang . '/login');
    }
}
