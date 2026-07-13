<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\firebaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\listsControllrt;
use Illuminate\Support\Facades\Session;


class firebaseController extends Controller
{
    public function sendNotification(Request $request)
    {
        $title = $request->notfTitle;
        $message = $request->notifContent;
        $tokens = [];
        $count = count($request->tokensNo);

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $rowId = $request->tokensNo[$i];
                $rowToken = $request['token_' . $rowId];
                array_push($tokens, $rowToken);
            }

            // exit('tokens is ' . implode('_', $tokens));
        } else {
            Session::flash(
                'status',
                'Please Select users to send notifications'
            );
            Session::flash('stype', 'danger');
            return redirect()->back();
        }

        $successStatus = 'Success send mobile notifications';
        $errorStatus = 'Failed to send notifications';

        $response = firebaseController::send($tokens, $title, $message);

        if ($response['success'] > 0) {
            Session::flash('status', $successStatus);
            Session::flash('stype', 'success');
        } else {
            Session::flash('status', $errorStatus);
            Session::flash('stype', 'danger');
        }

        return redirect()->back();
    }

    /**
     *  int actionType === action type number (2/accept - 3/reject - 4/post .. etc)
     *  array actionFor [] === users firebase tokens
     */
    public static function systemNotification(
        $actionType,
        $actionFor,
        $lang
    ) {

        //check if theres tokens
        if(count($actionFor) == 0) return "No tokens <br>";

        //step 1 : check if mobile notifications features  on/off
        $Features = listsControllrt::getFeaturesByNo("1"); // No
        if (count($Features) == 0) return "No Notifications Features   <br>";

        $Features = $Features->first();
        if ($Features->value != 'on') return "Notifications Off <br>";

        //step 2 : get system notification titles & messsages
        $Notication = listsControllrt::getNotifListBy($actionType)->first();
        $Title = $lang == 'en' ? $Notication->title_en : $Notication->title_ar;
        $Message = $lang == 'en' ? $Notication->msg_en : $Notication->msg_ar;

        //step 3 : send notifications
        $response = $this->send($actionFor, $Title, $Message);
        return $response;
    }

    //call fire base api
    public function send($tokens, $title, $message)
    {
        $FCM_SERVER = 'https://fcm.googleapis.com/fcm/send';
        $serverToken =
            'key=AAAAI6M558I:APA91bEMZqeOIet1evyhYGg46oAN0I5n_2fz6dCZuy86dSfhQspaZ5fTNjCGxqunAnRxGjMbqDNx3jqNEy7g3kw3kbLMkhT5ETE4Nbd_n4jIy3cRP7e4OYj4VFDtiE4lCdQMHTXAbITb';

        $response = Http::withOptions([
            'verify' => false,
        ])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $serverToken,
            ])
            ->post($FCM_SERVER, [
                'registration_ids' => $tokens,
                'priority' => 'high',
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                    // 'text' => 'Text dd',
                ],
            ]);

        return $response;
    }
}
