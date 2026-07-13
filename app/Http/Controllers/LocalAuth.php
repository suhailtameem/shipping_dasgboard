<?php

namespace App\Http\Controllers;
// App\Http\Controllers\LocalAuth;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Hash;
use App;

class LocalAuth extends Controller{

    public function autoRedirect(Request $request){
        switch($request->redirect){
            case 'updateUser':
                $this->adminUpdate($request);
                return redirect()->back();
            break;

            case 'reset':
                $this->resetPassword($request);
                return redirect()->back();
            break;

            case 'removeUser':
                $this->deleteUser($request);
                return redirect()->back();
            break;
        }
    }

    public function newUser(Request $request){
        $uimg = '/imgs/users/user.png';
        $create = User::create([
            'name'=>$request->input('userName'),
            'email'=>$request->input('emailAddr'),
            'title'=>$request->input('jobTitle'),
            'img'=>$uimg,
            'level'=>$request->input('userLevel'),
            'password'=>bcrypt($request->input('passCode')),
        ]);

        $this->sessionStatus($create);
        return redirect()->back();

    }

    public static function getUsers(){
        $users = User::get();
        return $users;
    }

    public function adminUpdate(Request $request){
        $update = User::where('id',$request->uid)->update([
            'name'=>$request->input('userName'),
            'email'=>$request->input('emailAddr'),
            'title'=>$request->input('jobTitle'),
            'level'=>$request->input('userLevel'),
        ]);
        $this->sessionStatus($update);
    }

    public function resetPassword(Request $request){
        $update = User::where('id',$request->uid)->update([
            'password'=>bcrypt($request->input('123456')),
        ]);
        $this->sessionStatus($update);
    }

    public function deleteUser(Request $request){
        $update = User::destroy($request->uid);
        $this->sessionStatus($update);
    }

    public function updateProfileImg(Request $request){
        $path = $this->saveImage($request->file('uimg'),'imgs/users');
        $update = User::where('id',$request->uid)->update([
            'img'=>$path,
        ]);

        //upddate image in session
        $sessiomTemp = Session::get('user');
        $sessiomTemp->img = $path;
        Session::put('user',$sessiomTemp);

        $this->sessionStatus($update);
        return redirect()->back();
    }

    public function saveImage($image,$Destnation){
        $extention = $image->getClientOriginalExtension();
        $imageName = $this->genrateNames().".".$extention;
        $outputFile = $Destnation.'/'.$imageName;

        $image->move($Destnation,$imageName);
        $Path = $Destnation."/".$imageName;

        return $Path;
    }




    public function updateBasic(Request $request){
        $update = User::where('id',$request->uid)->update([
            'name'=>$request->input('userName'),
            'title'=>$request->input('jobTitle'),
        ]);

        //upddate info in session
        $sessiomTemp = Session::get('user');
        $sessiomTemp->name = $request->input('userName');
        $sessiomTemp->title = $request->input('jobTitle');
        Session::put('user',$sessiomTemp);


        $this->sessionStatus($update);
        return redirect()->back();
    }

    public function changePassword(Request $request){
        $old = $request->passCode;
        $new =  $request->newPassCode;
        $confirm= $request->confPassCode;

        $info = User::select('password')->where('id',$request->uid)->get();
        $oldPass = $info[0]->password;

        if(Hash::check($old, $oldPass)){
            if($new == $confirm){
                $newPass = bcrypt($new);
                $update = User::where('id',$request->uid)->update([
                    'password'=>$newPass,
                ]);

                $this->sessionStatus($update);
                return redirect()->back();
            }else{exit('Your new password and it confirmation not matched...');}
        }else{exit('Your old password not correct...');}
    }


    public function Login(Request $request){

        //validate data at first
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //Check the email if exsist
        $user = User::where('email',$fields['email'])->first();

        //Check the password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            $request->session()->flash("status","Login failed Email/Password Not Correct.");
            return redirect()->back();
        }

        // $token = $user->createToken('myapptoken')->plainTextToken;
        $request->session()->put('user',$user);
        return redirect($request->lang.'/dashboard/');
    }

    public function Logout(Request $request){
        // $request->session()->flush();
        $request->session()->forget('user');
        return redirect($request->lang.'/users/login');
    }


    public function sessionStatus($State){
        if($State){
            Session::flash('status',' Action Complete Successfully');
            Session::flash('stype','success');
        }else{
            Session::flash('status','Action Not Complete');
            Session::flash('stype','danger');
        }
    }

    public function genrateNames(){
        $date = date('Y-m-d');
        $auto = rand( 10000 , 99999);
        return $date."-".$auto;
    }

}
