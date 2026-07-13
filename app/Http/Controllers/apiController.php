<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\apiController;
use App\Http\Controllers\settingsController as Settings;
use App\Http\Controllers\requestsControllrt;
use App\Http\Controllers\shipmentsController;

use Illuminate\Http\Request;
use App\Http\Controllers\appSetting;
use App\Models\company;

class apiController extends Controller
{

    public static function appStartup(Request $request)
    {

        $AppSettings = appSetting::getAppSettings();
        $AppSettCount = count($AppSettings);
        $settingsObj = $AppSettCount > 0 ? $AppSettings->first() : null;

        $mappedSettings = null;
        if ($settingsObj) {
            $mappedSettings = [
                'id' => $settingsObj->id,
                'app_version' => $settingsObj->version ?? '1.0',
                'force_update' => ($settingsObj->old === 'on') ? 1 : 0,
                'contact_phone' => $settingsObj->cs ?? '',
                'contact_email' => config('mail.from.address') ?? 'support@shipping.com',
                'terms_link' => $settingsObj->link ?? 'https://shipping.com/terms',
                // Keep original DB fields to avoid regression
                'power' => $settingsObj->power,
                'power_en' => $settingsObj->power_en,
                'power_ar' => $settingsObj->power_ar,
                'version' => $settingsObj->version,
                'old' => $settingsObj->old,
                'old_en' => $settingsObj->old_en,
                'old_ar' => $settingsObj->old_ar,
                'link' => $settingsObj->link,
                'legals_en' => $settingsObj->legals_en,
                'legals_ar' => $settingsObj->legals_ar,
                'cs' => $settingsObj->cs,
                'wsid' => $settingsObj->wsid,
            ];
        }

        $company = company::first();

        return response()->json([
            "appSettingStatus" => $AppSettCount,
            "appSettings" => $mappedSettings,
            "company" => $company,
        ]);
    }


    public function calculateRates(Request $request)
    {
        $shippingType = $request->input('shippingType');
        $countryFrom = $request->input('countryFrom');
        $countryTo = $request->input('countryTo');
        $weight = $request->input('weight');

        $currancies = Settings::showCurrancies();
        $totalPrice = requestsControllrt::calcWeightRate(
            $shippingType,
            $countryFrom,
            $countryTo,
            $weight
        );
        $response = response()->json([
            'totalPrice' => $totalPrice,
            'currList' => $currancies,
        ]);

        return $response;
    }

    public function getCustomerRequests(Request $request)
    {
        $cid = $request->input('cid');
        $requests = requestsControllrt::getRequestByCustomer($cid);
        return response()->json($requests);
    }

    public function trackingShipment(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'TNO' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'resultCode' => '-1',
                'resultStatus' => [
                    'en' => 'Tracking number is required.',
                    'ar' => 'رقم التتبع مطلوب.',
                ],
                'shipmentMovements' => [],
            ]);
        }

        $shipment = requestsControllrt::getShipmentRequestBy($request->TNO);
        $shipmentMovement = [];
        $resultCode = '';
        $resultStatus = [];

        if (count($shipment) > 0) {
            $resultCode = '1';
            foreach ($shipment as $key) {
                if ($key->shid != '-1') {
                    $trackingData = shipmentsController::trackingShipments($key->shid);
                    $resultStatus = [
                        'en' => 'Shipment tracked successfully',
                        'ar' => 'تم تتبع الشحنة بنجاح',
                    ];

                    // Map raw shipment movements into the expected status timeline format
                    $movements = $trackingData[1] ?? [];
                    $mappedMovements = [];
                    foreach ($movements as $move) {
                        $mappedMovements[] = [
                            'status' => $move->move == '1' ? 'In Transit' : 'Pending',
                            'details' => $move->details ?? '',
                            'location' => $move->location ?? '',
                            'timestamp' => $move->step_date ?? '',
                        ];
                    }
                    $shipmentMovement = $mappedMovements;
                } else {
                    $resultCode = '2';
                    $resultStatus = [
                        'en' => 'The shipment has not been shipped yet',
                        'ar' => 'لم يتم شحن الشحنة بعد',
                    ];
                }
            }
        } else {
            $resultCode = '-1';
            $resultStatus = [
                'en' => 'There is no request for this number',
                'ar' => 'لا يوجد طلب لهذا الرقم',
            ];
        }

        return response()->json([
            'resultCode' => $resultCode,
            'resultStatus' => $resultStatus,
            'shipmentMovements' => $shipmentMovement,
        ]);
    }
}
