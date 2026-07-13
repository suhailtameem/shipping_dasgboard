<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalAuth;
use App\Http\Controllers\shipmentsController;
use App\Http\Controllers\customerController as Customers;
use App\Http\Controllers\settingsController as Settings;
use App\Http\Controllers\requestsControllrt;
use App\Http\Controllers\ShippingRequestController;
use App\Http\Controllers\receiverController;
use App\Http\Controllers\firebaseController as Firebase;
use App\Http\Controllers\listsControllrt as Lists;
use App\Http\Controllers\appSetting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{lang}/dashboard/', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('dashboard');
});

Route::get('/{lang}/users/', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('users');
});

Route::get('/{lang}/users/login', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('usersLogin');
});

//@TODO /lang/passwordReset
Route::get('/{lang}/passwordReset', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    // return view('usersLogin');
    return "Change Your Password";
});



Route::get('/{lang}/Tracking', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('customer.tracking');
});

Route::get('/{lang}/Mobile', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('external.mobileSettings');
}); //

//============= Firebase ==============
Route::controller(Firebase::class)->group(function () {
    Route::post('/sendNotifi', 'sendNotification');
});

//============= Admin Authantcation ==============
Route::controller(LocalAuth::class)->group(function () {
    Route::post('/UserLogin', 'Login');
    Route::post('/newUser', 'newUser');
    Route::post('/actionUser', 'autoRedirect');
    Route::post('/proImg', 'updateProfileImg');
    Route::post('/basicInfo', 'updateBasic');
    Route::post('/updatePass', 'changePassword');
    Route::post('/userLogout', 'Logout');
});

//============= Shipping ==============

Route::get('/{lang}/test', function ($lang) {
    return 'Test Started';
});

Route::get('/{lang}/air-freight/', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('shipping.sh-air');
});

Route::get('/{lang}/sea-freight/', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('shipping.sh-sea');
});

Route::get('/{lang}/land-transport/', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('shipping.sh-land');
});

Route::get('/{lang}/addresses/{did}', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('shipping.address');
});

Route::get('/{lang}/movements/{id}/{shid}', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('shipping.movement');
});

Route::controller(shipmentsController::class)->group(function () {
    //Shipments
    Route::post('/addShipment', 'storeShipment');
    Route::post('/updateShipment', 'updateShipment');
    Route::post('/delShipment', 'destroyShipment');

    //Movements
    Route::post('/addMovements', 'storeMovements');
    Route::post('/updateMovement', 'updateMove');
    Route::post('/delMovement/{id}', 'destroyMove');

    //Shipments Desnations
    Route::post('/addDestnation', 'storeDesnation');
    Route::post('/upDestnation', 'updateDestnation');
    Route::post('/delDestnaion/{id}', 'destroyDestnation');

    //Shipments Desnations Address
    Route::post('/addDestAddress', 'storeAddress');
    Route::post('/updateDestAddress', 'editAddress');
    Route::post('/deleteAddress/{id}', 'deleteAddress');



});

//

//============= Requests Controller ==============
Route::get('/{lang}/sys-lists', [Lists::class, 'indexSystemLists']);
Route::post('/storeSysList', [Lists::class, 'storeSystemList']);
Route::post('/updateSysList', [Lists::class, 'updateSystemList']);
Route::get('/deleteSysList/{id}', [Lists::class, 'deleteSystemList']);

Route::post('/storeListItem', [Lists::class, 'storeListItem']);
Route::post('/updateListItem', [Lists::class, 'updateListItem']);
Route::get('/deleteListItem/{id}', [Lists::class, 'deleteListItem']);
Route::get('/toggleListSub/{id}', [Lists::class, 'toggleListSub']);

Route::post('/storeSubListItem', [Lists::class, 'storeSubListItem']);
Route::post('/updateSubListItem', [Lists::class, 'updateSubListItem']);
Route::get('/deleteSubListItem/{id}', [Lists::class, 'deleteSubListItem']);


Route::get('/{lang}/request-list', [requestsControllrt::class, 'requestList']);

Route::get('/{lang}/request/{RID}', [requestsControllrt::class, 'showRequestDetails']);
Route::get('/{lang}/create-request', [requestsControllrt::class, 'createRequest']);

Route::controller(requestsControllrt::class)->group(function () {
    //
    Route::post('/newShipment', 'storeRequest');

    Route::post('/upBasicInfo', 'updateBasicInfo');
    Route::post('/upRequestBasic', 'updateRequestBasic');
    Route::post('/delRequestBasic', 'deleteRequestBasic');

    Route::post('/upRequestContent', 'updateRequestContent');
    Route::post('/upPackage', 'updateContentRow');
    Route::post('/delPackage', 'deleteContentRow');
    Route::post('/updateOrderCurrency', 'updateOrderCurrency');
    Route::post('/adminAssignCustomer', [ShippingRequestController::class, 'assignCustomer']);
});

Route::controller(receiverController::class)->group(function () {
    Route::post('/storeReceiver', 'store');
    Route::post('/updateReceiver', 'updateReceiver');
    Route::post('/adminAssignReceiver', 'assignToRequest');
});

//============= Customer Side ==============

#Registraion
Route::get('/{lang}/signup', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('customer.signup');
});

Route::get('/{lang}/login', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('customer.login');
});

Route::get('/{lang}/home', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('customer.home');
});

Route::controller(Customers::class)->group(function () {
    Route::post('/newCustomer', 'signup');
    Route::post('/cusLogin', 'login');
    Route::post('/logout', 'logout');
    Route::post('/updatePassword', 'changePassword');
    Route::post('/updateBasic', 'updateBasic');
    Route::post('/adminUpdateCustomer', 'adminUpdateCustomer');
});

//============= Rates & Currancies ==============
Route::get('/{lang}/rates', function ($lang) {
    $ln = $lang == 'Ar' ? App::setLocale('ar') : App::setLocale('en');
    return view('rates.rates');
});

Route::controller(Settings::class)->group(function () {
    Route::post('/addCurrancy', 'storeCurrancy');
    Route::post('/updateCurrancy/{id}', 'updateCurrancy');
    Route::post('/deleteCurrancy/{id}', 'destroyCurrency');

    Route::post('/addRate', 'storeRates');
    Route::post('/updateRate/{id}', 'updateRate');
    Route::post('/deleteRate/{id}', 'destroyRate');

    Route::post('/addCountry', 'storeCountries');
    Route::post('/updateCountry/{id}', 'updateCountries');
    Route::post('/deleteCountry/{id}', 'destroyCountry');
});

//============= options lists and notifications list  ==============
Route::controller(Lists::class)->group(function () {
    Route::post('/updateNotifications', 'updateNotifList');
    //futchers
    Route::post('/updateFeature', 'updateFeature');
});



//============= Mobile App Settings ==============
Route::controller(appSetting::class)->group(function () {
    Route::post('/updateAppSetting', 'updateAppSettings');
});

//============= Company Settings ==============
Route::controller(\App\Http\Controllers\companyController::class)->group(function () {
    Route::post('/updateCompany', 'updateCompany');
});



//=== Shipment Expenses ===
Route::post('/saveExpenses',   [requestsControllrt::class, 'saveExpenses']);
Route::post('/updateExpense',  [requestsControllrt::class, 'updateExpense']);
Route::post('/deleteExpense',  [requestsControllrt::class, 'deleteExpense']);
Route::post('/bulkUpdateExpenses', [requestsControllrt::class, 'bulkUpdateExpenses']);
Route::post('/bulkDeleteExpenses', [requestsControllrt::class, 'bulkDeleteExpenses']);
    Route::post('/calculateLiveTotals', [requestsControllrt::class, 'calculateLiveTotals']);
    Route::get('/scan/{tno}', [requestsControllrt::class, 'handleQrScan']);

//=== Run Expenses seeder 
Route::get('/run-expenses-seeder', function () {
    Artisan::call('db:seed --class=ExpensesSeeder');
    return '<h2>ExpensesSeeder run successfully</h2>';
});

