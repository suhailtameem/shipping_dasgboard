<?php

namespace App\Http\Controllers;
// use App\Http\Controllers\shipmentsController;
use Illuminate\Http\Request;
use App\Models\{shipments, shMovements, shDestinations, destAddress};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime;



/*
    ====== shMovements ========
    'shid',
    'order',
    'step_date',
    'details',
    'location',

 */
class shipmentsController extends Controller
{
    ##============  Shipments   ===========

    public static function trackingShipments($shid)
    {
        $shipment = shipments::whereId($shid)->get();
        if (count($shipment) == 0) {
            return [];
        }
        foreach ($shipment as $ship) {
            //set destination from labels
            $destnations = shDestinations::whereId($ship->from)->get();
            foreach ($destnations as $dest) {
                $ship->from_ar = $dest->ar;
                $ship->from_en = $dest->destinations;
            }

            //set destination to labels
            $destnations = shDestinations::whereId($ship->to)->get();
            foreach ($destnations as $dest) {
                $ship->to_ar = $dest->ar;
                $ship->to_en = $dest->destinations;
            }

            //date format
            $ship->date = date('D, d M Y', strtotime($ship->created_at));
        }

        //get shipment movements
        $counter = 0;
        $doneMove = 0;
        $notDoneMove = 0;
        $firstMoveDate = '';
        $lastMoveDate = '';
        $todayDate = date('Y-m-d');
        $shMovements = shMovements::orderBy('step_date')
            ->where('shid', $shid)
            ->get();
        $length = count($shMovements);
        foreach ($shMovements as $shMove) {
            //get first move date and last move date
            switch ($counter) {
                case 1:
                    $firstMoveDate = new DateTime($shMove->step_date);
                    break;
                case $length - 1:
                    $lastMoveDate = new DateTime($shMove->step_date);
                    break;
            }

            $counter++;
            //check if move happened yet or not
            if (strtotime($todayDate) >= strtotime($shMove->step_date)) {
                $shMove->move = '1';
                $doneMove++;
            } else {
                $shMove->move = '0';
                $notDoneMove++;
            }
        }

        //calculate progress percantage
        $totalMoveCount = $doneMove + $notDoneMove;
        $parcantage = round(($doneMove / $totalMoveCount) * 100);

        //get days number btween first and last and now
        $todayDate = new DateTime($todayDate);
        $daysBtweenMoves = $lastMoveDate->diff($firstMoveDate)->format('%a');
        $daysBtweenTodayAndFirstMove = $todayDate
            ->diff($firstMoveDate)
            ->format('%a');

        return [
            $shipment,
            $shMovements,
            [
                [
                    'done' => $doneMove,
                    'notDoneMove' => $notDoneMove,
                    'parcantage' => $parcantage,
                    'days' => $daysBtweenMoves,
                    'remainingDays' => $daysBtweenTodayAndFirstMove,
                ],
            ],
        ];
    }

    ##============  Shipments   ===========
    public function storeShipment(Request $request)
    {
        $user = Auth::user()->id;
        $create = shipments::create([
            'container' => $request->input('conName'),
            'from' => $request->input('desFrom'),
            'to' => $request->input('desTo'),
            'progress' => $request->input('progress'),
            'pauto' => $request->input('pauto'),
            'sh_type' => $request->input('shType'),
            'Created_by' => $user,
        ]);

        if ($create) {
            $this->setFlash('s', 'Shipment added successfuly');
        } else {
            $this->setFlash('d', 'Failed to add shipment');
        }
        return redirect()->back();
    }

    public function updateShipment(Request $request)
    {
        $user = Auth::user()->id;
        $update = shipments::whereId($request->shid)->update([
            'container' => $request->input('conName'),
            'from' => $request->input('desFrom'),
            'to' => $request->input('desTo'),
            'progress' => $request->input('progress'),
            'pauto' => $request->input('pauto'),
            'updated_by' => $user,
        ]);

        if ($update) {
            $this->setFlash('s', 'Shipment updated successfuly');
        } else {
            $this->setFlash('d', 'Failed to update shipment');
        }
        return redirect()->back();
    }

    public function destroyShipment(Request $request)
    {
        $delete = shipments::destroy($request->shid);
        if ($delete) {
            $this->setFlash('s', 'Shipment deleted successfuly');
        } else {
            $this->setFlash('d', 'Failed to delete shipment');
        }
        return redirect()
            ->to($request->lang . '/air-freight')
            ->send();
    }

    public static function getShipments($shipmentType)
    {
        $Shipments = shipments::orderBy('id', 'desc')
            ->where('sh_type', $shipmentType)
            ->get();

        return $Shipments;
    }

    public static function getShipmentsRange(
        $shipmentType,
        $from,
        $to
    ) {
        $Shipments = shipments::orderBy('id', 'desc')
            ->where('sh_type', $shipmentType)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return $Shipments;
    }

    public static function getShipmentID($id)
    {
        $Shipment = shipments::where('id', $id)->get();

        return $Shipment;
    }

    ##============  Shipments Movements  ===========

    public function storeMovements(Request $request)
    {
        $leng = count($request->details);
        for ($i = 0; $i < $leng; $i++) {
            $create = shMovements::create([
                'shid' => $request->shid[$i],
                // 'order'=>$request,
                'step_date' => $request->date[$i],
                'details' => $request->details[$i],
                'location' => $request->locations[$i],
            ]);
        }

        if ($create) {
            $this->setFlash('s', '(' . $leng . ')Moves added successfully');
        } else {
            $this->setFlash('d', 'Failed to add Moves');
        }
        return redirect()->back();
    }

    public function updateMove(Request $request)
    {
        $update = shMovements::whereId($request->mid)->update([
            // 'order'=>$request->order,
            'step_date' => $request->date,
            'details' => $request->details,
            'location' => $request->locations,
        ]);

        if ($update) {
            $this->setFlash('s', 'Move Updated successfully');
        } else {
            $this->setFlash('d', 'Failed to update move');
        }
        return redirect()->back();
    }

    public function destroyMove($id)
    {
        $delete = shMovements::destroy($id);
        if ($delete) {
            $this->setFlash('s', 'Move deleted successfully');
        } else {
            $this->setFlash('d', 'Failed to delete move');
        }
        return redirect()->back();
    }

    public static function getMovements($shid)
    {
        $movements = shMovements::orderBy('step_date', 'asc')
            ->where('shid', $shid)
            ->get();
        return $movements;
    }

    public static function movesProgress($shid)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $movements = shMovements::where('shid', $shid)->get();
        $doneMoves = shMovements::where('shid', $shid)
            ->where('step_date', '<=', $today)
            ->get();
        $allMoves = count($movements) == 0 ? 1 : count($movements);
        $part = count($doneMoves);
        $progress = round(($part / $allMoves) * 100);
        return $progress;
    }

    ##============  Shipments Destnations  ===========
    public function storeDesnation(Request $request)
    {
        $create = shDestinations::create([
            'type' => $request->input('shType'),
            'destinations' => $request->input('shDest'),
            'ar' => $request->input('ar'),
        ]);

        if ($create) {
            $this->setFlash('s', 'Destnation added successfuly');
        } else {
            $this->setFlash('d', 'Failed to add destination');
        }
        return redirect()->back();
    }

    public function updateDestnation(Request $request)
    {
        $update = shDestinations::whereId($request->did)->update([
            'destinations' => $request->input('shDest'),
            'ar' => $request->input('ar'),
            'status' => $request->input('status'),
        ]);
        if ($update) {
            $this->setFlash('s', 'Destnation updated successfuly');
        } else {
            $this->setFlash('d', 'Failed to update destination');
        }
        return redirect()->back();
    }

    public function destroyDestnation($id)
    {
        $delete = shDestinations::destroy($id);
        if ($delete) {
            $this->setFlash('s', 'Destnation deleted');
        } else {
            $this->setFlash('d', 'Failed to delete destination');
        }
        return redirect()->back();
    }

    public static function getDestinations($shType){
        $destnations = shDestinations::where('type', $shType)->get();

        return $destnations;
    }


    public static function getActiveDest($shType)
    {
        $destnations = shDestinations::where('type', $shType)->where('status', '1')->get();
        return $destnations;
    }

    public static function getDestinationID($did)
    {
        $destnation = shDestinations::where('id', $did)->get();
        return $destnation;
    }



    ##============  Shipments Destnations  Address ===========

    public static function getAddressesBy($DID){
        $create =  destAddress::where('did',$DID)->get();
        return $create;
    }

    public function storeAddress(Request $request){
        $store = destAddress::create([
            "did"=>$request->did,
            "en"=>$request->en,
            "ar"=>$request->ar,
            "phone1"=>$request->phone1,
            "phone2"=>$request->phone2,
        ]);

        if($store){
            $this->setFlash("s", "Address Added Successfuly");
            return redirect()->back();
        }else{
            // exit('with '.$store);
            $this->setFlash("d", "Address Not Added");
            return redirect()->back();
        }
    }

    public function editAddress(Request $request){
        $update = destAddress::whereId($request->id)->update([
            "en"=>$request->en,
            "ar"=>$request->ar,
            "phone1"=>$request->phone1,
            "phone2"=>$request->phone2,
        ]);

        if($update){
            $this->setFlash("s", "Address Updated Successfuly");
            return redirect()->back();
        }else{
            $this->setFlash("d", "Address Not Updated");
            return redirect()->back();
        }
    }

    public function deleteAddress($id){
        $destroy = destAddress::destroy($id);
        if($destroy){
            $this->setFlash("s", "Address Deleted Successfuly");
            return redirect()->back();
        }else{
            $this->setFlash("d", "Address Not Deleted");
            return redirect()->back();
        }
    }




    ##============ Public functions ===========
    public function setFlash($type, $message)
    {
        Session::flash('status', $message);
        switch ($type) {
            case 's':
                Session::flash('stype', 'success');
                break;
            case 'd':
                Session::flash('stype', 'danger');
                break;
            case 'p':
                Session::flash('stype', 'primary');
                break;
        }
    }
}
