<?php

namespace App\Http\Controllers;

use App\Http\Controllers\shipmentsController;
use App\Http\Controllers\settingsController as Settings;
use App\Http\Controllers\firebaseController;
use App\Http\Controllers\listsControllrt ;
use App\Http\Controllers\receiverController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\{ShippingRequest, packages, customers as ShipCustomer, ShipmentExpense, ShipmentService, ExpenseType, currencies as Currency, company as Company, lists as ListModel, subList, shDestinations};
use App\Services\CurrencyService;

// use App\Http\Controllers\requestsControllrt;
class requestsControllrt extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    public function setFlash($type, $message)
    {
        Session::put('status', $message);
        Session::put('stype', $type);
    }

    public function statusColors($STNO)
    {
        switch ($STNO) {
            case '1':
                return "#0d6efd";//waiting
            case '2':
                return "#198754";//accepted
            case '3':
                return "#DC5252";//rejected
            case '4':
                return "#FDC136";//postnated
            default:
                return "#ccc";
        }
    }


    public function create()
    {
        //
    }

    public function requestList(Request $request, $lang)
    {
        // Set locale
        $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');

        // Initial Data
        $ReqState = $request->get('reqStatus', 0);
        $ShipType = $request->get('SHTYPE', 0);
        $shipment = $request->get('SHID', 0);
        $DFrom = $request->get('DFROM') ?: date('Y-m-d');
        $Dto = $request->get('DTO') ?: date('Y-m-d');

        // Filter Flags
        $filters = [
            'applyStatus' => $request->has('applyStatus'),
            'applyType' => $request->has('applyType'),
            'applyShipment' => $request->has('applyShipment'),
            'applyDate' => $request->has('applyDate'),
            'status' => $ReqState,
            'shipType' => $ShipType,
            'shipment' => $shipment,
            'dFrom' => $DFrom,
            'dDto' => $Dto
        ];

        $action = $request->get('action');

        // Get shipping requests based on action
        if ($action == '1') {
            $TrackingNo = $request->get('TNO');
            $ShippingRequests = self::searchReqTNo($TrackingNo);
        } elseif ($action == '2') {
            $ShippingRequests = $this->searchFilterDynamic($filters);
        } else {
            $ShippingRequests = self::indexShippingRequest();
        }

        // Eager load customer for better performance (and requested by user)
        $ShippingRequests->load('customer');

        // Prepare View Data
        $data = [
            'lang' => $lang,
            'dir' => $lang == "Ar" ? "rtl" : "ltr",
            'CenterArText' => $lang == "Ar" ? "text-center" : " ",
            'ShippingRequests' => $ShippingRequests,

            // Selection Lists
            'ShippingTypesList' => listsControllrt::getList(1),
            'ReqStatusList' => listsControllrt::getList(2),
            'ContainerTypesList' => listsControllrt::getList(3),
            'ServicesTypesList' => listsControllrt::getList(4),
            'CountriesList' => Settings::indexCountaries(),

            // Available shipments for selectors
            'AirCargos' => shipmentsController::getShipments('1'),
            'SeaContainers' => shipmentsController::getShipments('2'),
            'LandCharges' => shipmentsController::getShipments('3'),

            // Filter state preservation
            'ReqState' => $ReqState,
            'ShipType' => $ShipType,
            'shipment' => $shipment,
            'DFrom' => $DFrom,
            'Dto' => $Dto,

            // Filter Flags
            'applyStatus' => $filters['applyStatus'],
            'applyType' => $filters['applyType'],
            'applyShipment' => $filters['applyShipment'],
            'applyDate' => $filters['applyDate'],

            // Instance of controller for helper methods if needed (though we'll use @php or better, blade components)
            'controller' => $this
        ];

        return view('shipping.requests-list', $data);
    }

    /**
     * Store a newly created resource in storage. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //================  Basic Information ================

    public static function indexShippingRequest()
    {
        return ShippingRequest::with(['fromDest', 'toDest', 'customer', 'status', 'shippingType', 'containerType'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getRequestByCustomer(string $cid)
    {
        $index = ShippingRequest::with([
            'customer',
            'customer.senderCountry',
            'receiver',
            'receiver.recCountry',
            'fromDest',
            'toDest',
            'shippingType',
            'status',
            'packages.packageType'
        ])
            ->where('cid', $cid)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($index as $item) {
            $item->create_at = date('D, d M Y', strtotime($item->created_at));

            $item->fromEn = $item->fromDest->destinations ?? $item->from;
            $item->fromAr = $item->fromDest->ar ?? $item->from;
            $item->toEn = $item->toDest->destinations ?? $item->to;
            $item->toAr = $item->toDest->ar ?? $item->to;

            $item->sh_ar = $item->shippingType->ar ?? null;
            $item->sh_en = $item->shippingType->en ?? null;

            $item->status_ar = $item->status->ar ?? null;
            $item->status_en = $item->status->en ?? null;

            foreach ($item->packages as $pkg) {
                $pkg->cat_ar = $pkg->packageType->ar ?? null;
                $pkg->cat_en = $pkg->packageType->en ?? null;
            }
            $item->content = $item->packages;
        }

        return $index;
    }

    public static function getShipmentRequest($RID)
    {
        $shipment = ShippingRequest::with(['fromDest', 'toDest'])->whereId($RID)->get();
        foreach ($shipment as $item) {
            $item->fromEn = $item->fromDest->destinations ?? $item->from;
            $item->fromAr = $item->fromDest->ar ?? $item->from;
            $item->toEn = $item->toDest->destinations ?? $item->to;
            $item->toAr = $item->toDest->ar ?? $item->to;
        }
        $shipmentContent = packages::where('rid', $RID)->get();
        return [$shipment, $shipmentContent];
    }

    public static function getShipmentRequestBy($TNO)
    {
        return ShippingRequest::where('tno', $TNO)->get();
    }

    public function storeRequest(Request $request)
    {
        $succ = 'Shipping Request Saved Successfully';
        $fi = 'Shipping Request Not Saved';
        $TNO = $this->GenTNO();

        // Parse containerized and clearance as comma-separated strings of non-numeric values
        $containerized = collect($request->input('containerType'))->filter(function($val) {
            return !is_numeric($val);
        })->implode(', ');

        $clearnce = collect($request->input('serviceType'))->filter(function($val) {
            return !is_numeric($val);
        })->implode(', ');

        $store = ShippingRequest::create([
            #Form inputs
            'sh_type' => $request->input('shippType'),
            'containerized' => $containerized ?: null,
            'clearnce' => $clearnce ?: null,
            'from' => $request->input('fromCountry'),
            'to' => $request->input('toCountry'),
            #Form hidden inputs
            'getway' => $request->input('getway'), //1-admin 2-user 3-other
            'getway_type' => '1', //1-web 2-mobile 3-other
            'cid' => null,
            #Defoult
            'total_weight' => '0',
            'shid' => '-1',
            'req_status' => '4', //waiting
            'step' => '0',
            #Proccessed
            'tno' => $TNO,
        ]);

        if ($store) {
            $RID = $store->id;

            // ── Save selected sub-list items (containers & services) ──
            $selectedSubIds = array_filter(
                array_merge(
                    (array) $request->input('containerType', []),
                    (array) $request->input('serviceType', [])
                ),
                'is_numeric'
            );

            if (!empty($selectedSubIds)) {
                $subItems = subList::with('parentList')->whereIn('id', $selectedSubIds)->get();
                foreach ($subItems as $sub) {
                    $parentEn = $sub->parentList->en ?? '';
                    $parentAr = $sub->parentList->ar ?? '';
                    ShipmentService::create([
                        'shipment_id' => $RID,
                        'sub_list_id' => $sub->id,
                        'title_en'    => $sub->en . ($parentEn ? ' (' . $parentEn . ')' : ''),
                        'title_ar'    => $sub->ar . ($parentAr ? ' (' . $parentAr . ')' : ''),
                        'price'       => $sub->price ?? 0,
                        'quantity'    => 1,
                    ]);
                }
            }

            $lang = $request->lang;
            $this->Response($succ, 'success', 'web');
            return redirect()
                ->to('/' . $lang . '/request/' . $RID)
                ->send();
        } else {
            $this->Response($fi, 'danger', 'web');
            return redirect()->back();
        }
    }

    public function storeApiRequest(Request $request)
    {
        $succ = 'Shipping Request Saved Successfully';
        $fi = 'Shipping Request Not Saved';

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'cuid' => 'required|exists:customers,id',
            'sender' => 'required|string|max:255',
            'senderPhone' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'receiverPhone' => 'required|string|max:255',
            'shippType' => 'required|string',
            'fromCountry' => 'required|string',
            'toCountry' => 'required|string',
            'totalWeight' => 'required|numeric',
            'name' => 'required|array',
            'type' => 'required|array',
            'weight' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                'stype' => 'danger',
                'data' => [],
            ]);
        }

        $TNO = $this->GenTNO();

        $store = ShippingRequest::create([
            #Personal information
            'send_name' => $request->input('sender'),
            'send_phone' => $request->input('senderPhone'),
            'rec_name' => $request->input('receiver'),
            'rec_phone' => $request->input('receiverPhone'),
            'rec_phone2' => $request->input('receiverPhone2'),

            #basic information
            'sh_type' => $request->input('shippType'),
            'containerized' => $request->input('containerType'),
            'clearnce' => $request->input('serviceType'),
            'from' => $request->input('fromCountry'),
            'to' => $request->input('toCountry'),

            #Form hidden inputs
            'getway' => $request->input('getway'), //1-admin 2-user 3-other
            'getway_type' => $request->input('getwayType'), //1-web 2-mobile 3-other
            'cid' => $request->input('cuid'),

            #Defoult
            'total_weight' => $request->input('totalWeight'),
            'shid' => '-1',
            'req_status' => '4', //waiting
            'step' => $request->input('step'),
            #Proccessed
            'tno' => $TNO,
        ]);

        $this->addRequestContent($request, $store->id);

        if ($store) {
            //status stype App
            return $this->Response($succ, 'success', 'mobile');
        } else {
            return $this->Response($fi, 'danger', 'mobile');
        }
    }

    public function updateBasicInfo(Request $request)
    {
        $RID = $request->input('RID');
        $update = ShippingRequest::whereId($RID)->update([
            'sh_type' => $request->input('shippType'),
            'containerized' => $request->input('containerType'),
            'clearnce' => $request->input('serviceType'),
            'from' => $request->input('fromCountry'),
            'to' => $request->input('toCountry'),
        ]);

        if ($update) {
            //status stype App
            // $totalWeight = $this->calcTotalWeight($RID);
            $succ = 'Basic information updated / Prices maybe changed';
            $this->Response($succ, 'success', 'web');
            return redirect()->back();
        } else {
            $fi = 'Basic information Not updated';
            $this->Response($fi, 'danger', 'web');
            return redirect()->back();
        }
    }

    public function updateRequestBasic(Request $request)
    {
        $CheckBox = $request->input('CheckBox');
        $Length = count($CheckBox);
        $updates = 0;
        $drops = 0;


        //prepare to send notifications
        $IDS_Statusies = [];
        for ($i = 0; $i < $Length; $i++) {
            $RID = $CheckBox[$i];
            $item = [
                "id" => $RID,
                'status' => $request->input('reqStatus_' . $RID),
                "shid" => $request->input('shid_' . $RID)
            ];
            array_push($IDS_Statusies, $item);
        }
        //check if theres new or any change if not dont send send notifications
        $tokens = requestsControllrt::getUsersToken($IDS_Statusies);

        //update shipping requests
        for ($i = 0; $i < $Length; $i++) {
            $RID = $CheckBox[$i];
            $update = ShippingRequest::whereId($RID)->update([
                'containerized' => $request->input('containerType_' . $RID),
                'total_weight' => $request->input('totalWeight_' . $RID),
                'req_status' => $request->input('reqStatus_' . $RID),
                // 'sh_type'=>$request->input('shippType_'.$RID),
                'shid' => $request->input('shid_' . $RID),
            ]);

            if ($update)
                $updates++;
            else
                $drops++;
        }



        //send notifications for filtered and selected users
        for ($i = 2; $i < 6; $i++) {
            // i(2 - 4) request satus
            for ($y = 0; $y < count($tokens['en'][$i]); $y++) {
                $result = firebaseController::systemNotification($i, $tokens['en'][$i], 'en');
                // echo($result);
            }

            for ($y = 0; $y < count($tokens['ar'][$i]); $y++) {
                $result = firebaseController::systemNotification($i, $tokens['ar'][$i], 'ar');
                // echo($result);
            }
        }




        $succ = "Updated $updates rows, Failed to update $drops rows";
        $this->Response($succ, 'success', 'web');
        return redirect()->back();
    }

    public static function getUsersToken($requestsIDS)
    {
        $tokensEn = [
            "1" => [],
            "2" => [],
            "3" => [],
            "4" => [],
            "5" => [],
        ];
        $tokensAr = [
            "1" => [],
            "2" => [],
            "3" => [],
            "4" => [],
            "5" => [],
        ];

        $ids = collect($requestsIDS)->pluck('id');
        $requests = ShippingRequest::with('customer')->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($requestsIDS as $item) {
            $reqInfo = $requests->get($item['id']);
            if (!$reqInfo) {
                continue;
            }

            $customer = $reqInfo->customer;
            if (!$customer) {
                continue;
            }

            $alerStatus = $reqInfo->req_status != $item["status"];
            $alertShipping = $reqInfo->shid != $item["shid"] && $item["shid"] != '-1';

            $token = $customer->token;
            if ($token) {
                if ($customer->lang == 'ar') {
                    if ($alerStatus) {
                        array_push($tokensAr[$item["status"]], $token);
                    }
                    if ($alertShipping) {
                        array_push($tokensAr["5"], $token);
                    }
                } else {
                    if ($alerStatus) {
                        array_push($tokensEn[$item["status"]], $token);
                    }
                    if ($alertShipping) {
                        array_push($tokensEn["5"], $token);
                    }
                }
            }
        }

        return [
            'en' => $tokensEn,
            'ar' => $tokensAr,
        ];
    }

    public function deleteRequestBasic(Request $request)
    {
        $CheckBox = $request->input('CheckBox');
        $Length = count($CheckBox);
        $updates = 0;
        $drops = 0;

        for ($i = 0; $i < $Length; $i++) {
            $RID = $CheckBox[$i];
            $delete = ShippingRequest::destroy($RID);
            if ($delete) {
                $updates++;
            } else {
                $drops++;
            }
        }

        $succ = "$updates rows deleted, Failed to delete $drops rows";
        $this->Response($succ, 'success', 'web');
        return redirect()->back();
    }

    //================  Content Information ================

    public function updateRequestContent(Request $request)
    {
        $RID = $request->input('rid');

        $update = ShippingRequest::whereId($RID)->update([
            //inputs
            'Comment' => $request->input('note'),
            //hidden inputs
            'total_weight' => $request->input('totalWeight'),
            'total_price' => $request->input('totalPrices'),
            'updated_by' => $request->input('cuid'),
            'step' => $request->input('2'),
        ]);

        $this->addRequestContent($request);
        if ($update) {
            $succ = 'Request updated successfully';
            $this->Response($succ, 'success', 'web');
            return redirect()->back();
        } else {
            $fi = 'Failed to update request';
            $this->Response($fi, 'danger', 'web');
            return redirect()->back();
        }
    }

    public function addRequestContent(Request $request, $rid = null)
    {
        $RID = $rid ?? $request->input('rid') ?? $request->rid;

        if (!$request->input('name')) {
            return;
        }
        $Length = count($request->input('name'));

        $totalWeight = 0.0;
        $totalPrice = 0.0;

        for ($i = 0; $i < $Length; $i++) {
            $create = packages::create([
                'rid' => $RID,
                'name' => $request->input('name')[$i],
                'ptype' => $request->input('type')[$i],
                'weight' => $request->input('weight')[$i],
                // 'price',
                // 'description',
            ]);
        }

        // $totalWeight = $this->calcTotalWeight($RID);
    }

    public function updateContentRow(Request $request)
    {
        $RID = $request->input('rid');
        $shipment = ShippingRequest::findOrFail($RID);
        $currencyId = $shipment->currency_id;
        if (!$currencyId) {
            $usd = Currency::where('currency', 'USD')->first();
            $currencyId = $usd ? $usd->id : 1;
        }

        $submittedPrice = (float) $request->input('price');
        $priceInUsd = $this->currencyService->convertCurrencyToUsd($submittedPrice, $currencyId);

        $update = packages::whereId($request->rowID)->update([
            'name' => $request->input('name'),
            'ptype' => $request->input('type'),
            'weight' => $request->input('weight'),
            'price' => $priceInUsd,
        ]);

        //update total weight and total price
        $this->calcTotalWeight($RID);
        if ($update) {
            $succ = 'Package updated successfully';
            $this->Response($succ, 'success', 'web');
            return redirect()->back();
        } else {
            $fi = 'Failed to update this package';
            $this->Response($fi, 'danger', 'web');
            return redirect()->back();
        }
    }

    public function deleteContentRow(Request $request)
    {
        $RID = $request->input('rid');
        $destroy = packages::destroy($request->rowID);

        //update total weight and total price
        $this->calcTotalWeight($RID);
        if ($destroy) {
            $succ = 'Package deleted successfully';
            $this->Response($succ, 'success', 'web');
            return redirect()->back();
        } else {
            $fi = 'Failed to delete this package';
            $this->Response($fi, 'danger', 'web');
            return redirect()->back();
        }
    }

    //================  Search Information ================

    public function searchFilterDynamic($filters)
    {
        $query = ShippingRequest::with(['fromDest', 'toDest', 'customer', 'status', 'shippingType', 'containerType']);

        if ($filters['applyStatus'] && $filters['status'] != '0') {
            $query->where('req_status', $filters['status']);
        }

        if ($filters['applyType'] && $filters['shipType'] != '0') {
            $query->where('sh_type', $filters['shipType']);
        }

        if ($filters['applyShipment'] && $filters['shipment'] != '0') {
            $query->where('shid', $filters['shipment']);
        }

        if ($filters['applyDate']) {
            $query->whereDate('created_at', '>=', $filters['dFrom'])
                ->whereDate('created_at', '<=', $filters['dDto']);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public static function searchReqTNo($TrackingNo)
    {
        return ShippingRequest::with(['fromDest', 'toDest', 'customer', 'status', 'shippingType', 'containerType'])
            ->where('tno', $TrackingNo)
            ->get();
    }

    //==================== Helper functions ================

    public function calcTotalWeight($RID): void
    {
        $shippingRequest = ShippingRequest::with('packages')->findOrFail($RID);
        $totalWeight = $shippingRequest->packages()->sum('weight');

        // Fallback to USD if no currency is set
        $currencyId = $shippingRequest->currency_id;
        if (!$currencyId) {
            $usd = Currency::where('currency', 'USD')->first();
            $currencyId = $usd ? (int) $usd->id : 1;
        } else {
            $currencyId = (int) $currencyId;
        }

        // Calculate total price in the order's specific currency ID
        $totalPrice = $this->currencyService->calculateOrderContentsTotal(
            $shippingRequest->packages,
            $shippingRequest->sh_type,
            $shippingRequest->from,
            $shippingRequest->to,
            $currencyId
        );

        $shippingRequest->update([
            'total_weight' => $totalWeight,
            'total_price' => $totalPrice,
            'req_status' => '4', // return status to waiting
        ]);
    }

    public static function inRange($n, $nStart, $nEnd)
    {
        if ($n >= $nStart && $n <= $nEnd) {
            return true;
        } else {
            return false;
        }
    }

    //================  Api Application ================



    //Helper functions
    public function checkTNO($TNO)
    {
        $check = ShippingRequest::where('tno', $TNO)->get();
        if (count($check) == 0) {
            return true;
        }
        return false;
    }

    public function GenTNO()
    {
        $chars =
            '1 2 3 4 5 6 7 8 9 A B C D E F G H I J K L M N P Q R S T U V W X T Z'; //Not used Letter[O] No [0]
        $chArr = explode(' ', $chars);
        $randNo = '';
        for ($i = 0; $i < 5; $i++) {
            $rand = rand(0, 33);
            $randNo = $randNo .= $chArr[$rand];
        }

        while ($this->checkTNO($randNo) == false) {
            $this->GenTNO();
        }

        return $randNo;
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

    public function showRequestDetails($lang, $RID)
    {
        // Set locale
        $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');

        // Fetch shipment with relationships
        $shipment = ShippingRequest::with([
            'customer.country',
            'receiver.country',
            'packages',
            'expenses.expenseType',
            'fromDest',
            'toDest',
            'shippingType',
            'containerType',
            'serviceType',
            'status',
            'shipmentServices',
        ])->findOrFail($RID);

        // Get base totals from service using currency ID
        $currencyId = $shipment->currency_id;
        if (!$currencyId) {
            $usd = Currency::where('currency', 'USD')->first();
            $currencyId = $usd ? $usd->id : 1; // Default to 1 if USD not found
        }

        // Prepare view data array
        $data = [
            'lang' => $lang,
            'dir' => $lang == "Ar" ? "rtl" : "ltr",
            'CenterArText' => $lang == "Ar" ? "text-center" : " ",
            'shipment' => $shipment,
            'CountriesList' => Settings::indexCountaries(),
            'CustomersList' => ShipCustomer::get(),

            // Available shipments
            'AirCargos' => shipmentsController::getShipments('1'),
            'SeaContainers' => shipmentsController::getShipments('2'),
            'LandCharges' => shipmentsController::getShipments('3'),

            // Selection Lists
            'ShippingTypesList' => listsControllrt::getList(1),
            'ReqStatusList' => listsControllrt::getList(2),
            'ContainerTypesList' => listsControllrt::getList(3),
            'ServicesTypesList' => listsControllrt::getList(4),
            'PackagesType' => listsControllrt::getList(5),

            // Domain Data
            'shTypeInfo' => $this->getshTypeInfo($shipment->sh_type),
            'airDests' => self::getDestInfo(1),
            'seaDests' => self::getDestInfo(2),
            'landDests' => self::getDestInfo(3),
            'ShippingRates'  => Settings::indexShippingRatesBy($shipment->sh_type, $shipment->from, $shipment->to),
            'ReceiversList'  => receiverController::getReceiversByCustomer($shipment->cid),
            'cuid'          => $this->getCuidByGateway($shipment->getway),

            // Expenses
            'expenses'          => $shipment->expenses,
            'expenseTypes'      => ExpenseType::where('is_active', true)->get(),
            'totalExpenses'     => $this->currencyService->calculateOrderExpensesTotal($shipment->expenses),
            'totalServices'     => $this->currencyService->calculateOrderServicesTotal($shipment->shipmentServices, $currencyId),
            'finalTotal'        => $this->currencyService->calculateFinalInvoice($shipment->packages, $shipment->expenses, $shipment->sh_type, $shipment->from, $shipment->to, $currencyId, $shipment->shipmentServices),
            'currencies'        => Currency::orderBy('currency')->get(),

            // Shipment Services (containers & additional services)
            'shipmentServices'  => $shipment->shipmentServices,
            'allSubLists'       => subList::with('parentList')->orderBy('list_id')->get(),
            'currencyService'   => $this->currencyService,
            'company'       => Company::first() ?? (object)[
                'name_en' => 'Shipping Co.', 
                'name_ar' => 'شركة الشحن', 
                'email' => 'info@shipping.com', 
                'phone' => '+123456789'
            ],
        ];

        return view('shipping.requests-admin', $data);
    }

    private function getCuidByGateway($gateway)
    {
        switch ($gateway) {
            case '1':
                return Session::get('user')->id ?? 0;
            case '2':
                return Session::get('customer')->id ?? 0;
            default:
                return 0;
        }
    }

    public function getshTypeInfo($shid)
    {
        switch ($shid) {
            case '1':
                return [__('lang.AirFTapTitle'), 'Cargos', '<i class="bi bi-send-fill"></i>', '/air-freight'];
            case '2':
                return [__('lang.SearFTapTitle'), 'Containers', '<i class="las la-ship"></i>', '/sea-freight'];
            case '3':
                return [__('lang.LandFTapTite'), 'Shipment', '<i class="las la-shipping-fast"></i>', '/land-transport'];
            default:
                return ['', '', '', ''];
        }
    }

    public static function getDestInfo($shippingType)
    {
        switch ($shippingType) {
            case 1:
                return shipmentsController::getDestinations('1');
            case 2:
                return shipmentsController::getDestinations('2');
            case 3:
                return shipmentsController::getDestinations('3');
            default:
                return [];
        }
    }

    // ============= Shipment Expenses =============

    public function updateOrderCurrency(Request $request)
    {
        $RID           = $request->input('rid');
        $newCurrencyId = (int) $request->input('currency_id');

        $shippingRequest = ShippingRequest::with('expenses')->findOrFail($RID);

        // Determine the old currency (fallback to USD)
        $oldCurrencyId = (int) ($shippingRequest->currency_id ?? 0);
        if (!$oldCurrencyId) {
            $usd           = Currency::where('currency', 'USD')->first();
            $oldCurrencyId = $usd ? (int) $usd->id : 1;
        }

        // Convert all existing expense amounts: old currency -> USD -> new currency
        if ($oldCurrencyId !== $newCurrencyId && $shippingRequest->expenses->isNotEmpty()) {
            foreach ($shippingRequest->expenses as $expense) {
                $amountInUsd    = $this->currencyService->convertCurrencyToUsd((float) $expense->amount, $oldCurrencyId);
                $amountInNewCur = $this->currencyService->convertUsdToCurrency($amountInUsd, $newCurrencyId);
                $expense->update(['amount' => round($amountInNewCur, 4)]);
            }
        }

        // Update currency ID and trigger package/service total recalculation
        $shippingRequest->update(['currency_id' => $newCurrencyId]);
        $this->calcTotalWeight($RID);

        $this->Response('Order currency updated and totals recalculated', 'success', 'web');
        return redirect()->back();
    }

    public function saveExpenses(Request $request)
    {
        $RID      = $request->input('rid');
        $typeIds  = $request->input('expense_type_id', []);
        $amounts  = $request->input('amount', []);
        $notes    = $request->input('notes', []);

        foreach ($typeIds as $i => $typeId) {
            if (!$typeId) continue;
            ShipmentExpense::create([
                'shipment_id'     => $RID,
                'expense_type_id' => $typeId,
                'amount'          => $amounts[$i] ?? 0,
                'created_by'      => Session::get('user')?->id,
                'notes'           => $notes[$i] ?? null,
            ]);
        }

        $this->Response('Expenses saved successfully', 'success', 'web');
        return redirect()->back();
    }

    public function updateExpense(Request $request)
    {
        $expId = $request->input('exp_id');
        $exp   = ShipmentExpense::findOrFail($expId);

        $exp->update([
            'expense_type_id' => $request->input('expense_type_id'),
            'amount'          => $request->input('amount'),
            'notes'           => $request->input('notes'),
        ]);

        $this->Response('Expense updated successfully', 'success', 'web');
        return redirect()->back();
    }

    public function calculateLiveTotals(Request $request)
    {
        $rid        = $request->input('rid');
        $weights    = $request->input('weights', []);
        $expenses   = $request->input('expenses', []);
        $currencyId = $request->input('currency_id');

        // Load shipment with both packages (for accurate pricing) and services
        $shipment = ShippingRequest::with(['packages', 'shipmentServices'])->findOrFail($rid);

        if (!$currencyId) {
            $currencyId = $shipment->currency_id;
        }
        if (!$currencyId) {
            $usd        = Currency::where('currency', 'USD')->first();
            $currencyId = $usd ? $usd->id : 1;
        }
        $currencyId = (int) $currencyId;

        // 1. Total Weight (from live form inputs)
        $totalWeight = array_sum(array_map('floatval', $weights));

        // 2. Contents Total — use actual saved packages (respects custom prices stored in USD)
        $contentsTotal = $this->currencyService->calculateOrderContentsTotal(
            $shipment->packages,
            $shipment->sh_type,
            $shipment->from,
            $shipment->to,
            $currencyId
        );

        // 3. Expenses Total — amounts are stored in the order currency, just sum them
        $expensesTotal = array_sum(array_map('floatval', $expenses));

        // 4. Services Total — prices stored in USD, convert to order currency
        $servicesTotal = $this->currencyService->calculateOrderServicesTotal(
            $shipment->shipmentServices,
            $currencyId
        );

        // 5. Grand Total = Contents + Expenses + Services
        $finalTotal = $contentsTotal + $expensesTotal + $servicesTotal;

        return response()->json([
            'totalWeight'   => $totalWeight,
            'contentsTotal' => $contentsTotal,
            'expensesTotal' => $expensesTotal,
            'servicesTotal' => $servicesTotal,
            'finalTotal'    => $finalTotal,
            'currency'      => Currency::find($currencyId)->currency ?? 'USD',
        ]);
    }

    public function deleteExpense(Request $request)
    {
        $expId = $request->input('exp_id');
        ShipmentExpense::findOrFail($expId)->delete();

        $this->Response('Expense deleted successfully', 'success', 'web');
        return redirect()->back();
    }

    public function handleQrScan($tno)
    {
        $shipment = ShippingRequest::where('tno', $tno)->firstOrFail();
        
        // If logged in as admin
        if (Session::has('user')) {
            $lang = App::getLocale() == 'ar' ? 'Ar' : 'En';
            return redirect('/' . $lang . '/request/' . $shipment->id);
        }

        // Public tracking redirect
        $lang = App::getLocale() == 'ar' ? 'Ar' : 'En';
        return redirect('/' . $lang . '/Tracking?TNO=' . $tno);
    }

    public function bulkUpdateExpenses(Request $request)
    {
        $expenseIds = $request->input('expense_ids', []);
        
        foreach ($expenseIds as $expId) {
            $expense = ShipmentExpense::findOrFail($expId);
            $expense->update([
                'expense_type_id' => $request->input("expense_type_id_{$expId}"),
                'amount'          => $request->input("amount_{$expId}"),
                'notes'           => $request->input("notes_{$expId}"),
            ]);
        }

        $this->Response('Expenses updated successfully', 'success', 'web');
        return redirect()->back();
    }

    public function bulkDeleteExpenses(Request $request)
    {
        $selectedExpenses = $request->input('selected_expenses', []);
        
        if (!empty($selectedExpenses)) {
            ShipmentExpense::whereIn('id', $selectedExpenses)->delete();
        }

        $this->Response('Selected expenses deleted successfully', 'success', 'web');
        return redirect()->back();
    }

    public function createRequest($lang)
    {
        // Set locale
        $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');

        // Get all shipping types with images
        $shippingTypes = ListModel::where('lid', 1)->with('subLists')->get();

        // Get container types with sub-lists and images
        $containerTypes = ListModel::where('lid', 3)->with('subLists')->get();

        // Get service types with sub-lists and images
        $serviceTypes = ListModel::where('lid', 4)->with('subLists')->get();

        // Get all destinations
        $airDests = self::getDestInfo(1);
        $seaDests = self::getDestInfo(2);
        $landDests = self::getDestInfo(3);

        $data = [
            'lang' => $lang,
            'dir' => $lang == 'Ar' ? 'rtl' : 'ltr',
            'CenterArText' => $lang == "Ar" ? "text-center" : " ",
            'shippingTypes' => $shippingTypes,
            'containerTypes' => $containerTypes,
            'serviceTypes' => $serviceTypes,
            'airDests' => $airDests,
            'seaDests' => $seaDests,
            'landDests' => $landDests,
        ];

        return view('shipping.create-request', $data);
    }

    // ============= Shipment Services CRUD =============

    public function storeShipmentService(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|exists:shipping_requests,id',
            'sub_list_id' => 'nullable|exists:sub_lists,id',
            'title_en'    => 'required|string|max:255',
            'title_ar'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:1',
        ]);

        ShipmentService::create([
            'shipment_id' => $request->shipment_id,
            'sub_list_id' => $request->sub_list_id,
            'title_en'    => $request->title_en,
            'title_ar'    => $request->title_ar,
            'price'       => $request->price,
            'quantity'    => $request->quantity,
        ]);

        $this->Response('Service added successfully', 'success', 'web');
        return redirect()->back();
    }

    public function updateShipmentService(Request $request)
    {
        $request->validate([
            'service_id'  => 'required|exists:shipment_services,id',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:1',
            'shipment_id' => 'required|exists:shipping_requests,id',
        ]);

        // The submitted price is in the order currency (displayed converted from USD).
        // Convert it back to USD before storing so all service prices stay in USD.
        $shipment   = ShippingRequest::findOrFail($request->shipment_id);
        $currencyId = (int) ($shipment->currency_id ?? 0);
        if (!$currencyId) {
            $usd        = Currency::where('currency', 'USD')->first();
            $currencyId = $usd ? (int) $usd->id : 1;
        }
        $priceInUsd = $this->currencyService->convertCurrencyToUsd((float) $request->price, $currencyId);

        ShipmentService::whereId($request->service_id)->update([
            'price'    => $priceInUsd,
            'quantity' => $request->quantity,
        ]);

        $this->Response('Service updated successfully', 'success', 'web');
        return redirect()->back();
    }

    public function deleteShipmentService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:shipment_services,id',
        ]);

        ShipmentService::destroy($request->service_id);

        $this->Response('Service deleted successfully', 'success', 'web');
        return redirect()->back();
    }
}