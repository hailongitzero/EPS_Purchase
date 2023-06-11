<?php

use App\Http\Controllers\AssetRequestablesController;
use App\Http\Controllers\AssetsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceManagePageController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LicensesController;
use App\Http\Controllers\MaintenancesController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RequestHandleController;
use App\Http\Controllers\RequestTypeController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Adldap\Laravel\Facades\Adldap;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request as HttpRequest;

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

Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');

// Route::get('test-schema', [RequestController::class, 'testSchema']);

Route::middleware('loggedin')->group(function() {
    Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendRequest'])->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.reset.view');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.reset');
});

Route::middleware('auth')->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');

    //dashboard data
    Route::post('/department-request-period', [DashboardController::class, 'getDepartmentRequestPeriod']);
    Route::post('/request-group-period', [DashboardController::class, 'getRequestGroupByPeriod']);
    Route::post('/request-statistics-period', [DashboardController::class, 'getRequestGroupByPeriod']);
    Route::post('/latest-activity-request', [DashboardController::class, 'getLatestActivity']);

    //request get route
    Route::get('make-request', [PageController::class, 'makeRequest'])->name('request.add.view');

    // request Post route
    Route::post('/get-request', [RequestController::class, 'get'])->name('request.get');
    Route::post('/add-request', [RequestController::class, 'add'])->name('request.add');

    //others
    Route::get('profile', [PageController::class, 'profile'])->name('profile.view');
    Route::post('ckupload', [FileUploadController::class, 'ckUploadImage'])->name('ckUploadImage');
    Route::get('get-request-type', [RequestTypeController::class, 'getRequestType']);
    Route::get('get-handler', [UserController::class, 'getModerator']);
    Route::get('/my-requests', [PageController::class, 'myRequests'])->name('myRequests');
    // get self request
    Route::get('/my-request', [RequestController::class, 'myRequests']);
    //download attachment file
    Route::get('/download/{id}', [FileDownloadController::class, 'downloadAttachment']);

    // user profile
    Route::get('/profile/{username}', [PageController::class, 'profile'])->name('user.view');
    Route::post('/update-profile', [UserController::class, 'update']);
    Route::get('/profile/{username}/device', [PageController::class, 'profile'])->name('user.device');

    Route::post('/change-password', [UserController::class, 'changePassword']);

    // ckfinder
    // Route::get('file-manage', function(){
    //     return view('vendor.ckfinder.browser');
    // });
    Route::get('file-manage', [PageController::class, 'fileManage'])->name('fileManage');
    Route::any('/ckfinder/connector',  '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');
    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');

    Route::get('requestable', [DeviceManagePageController::class, 'getRequestables'])->name('requestables');
    Route::get('requestable-list', [DeviceManagePageController::class, 'getRequestableList'])->name('requestables.list');
    Route::get('requestables/get', [AssetsController::class, 'getRequestables'])->name('requestables.get');
    Route::post('requestable/add', [AssetRequestablesController::class, 'add']);
    Route::get('requestable/request/{id}', [DeviceManagePageController::class, 'getRequestableById'])->name('requestables.list');
    Route::post('requestable-cancel', [AssetRequestablesController::class, 'delete']);
});
// Admin route
Route::middleware(['auth','isAdmin'])->group(function() {
    Route::get('administrator', [PageController::class, 'adminRequestManager'])->name('administrator');
    // get new requests
    Route::get('/new-requests', [RequestController::class, 'newRequest']);
    // get handling request
    Route::get('/handling-request', [RequestController::class, 'handlingRequest']);
    // assign request
    Route::post('/assign-request', [RequestHandleController::class, 'assignRequest']);
    // extend request decide
    Route::post('/extend-request-decide', [RequestHandleController::class, 'extendRequestDecide']);
    // assign return request
    Route::post('/assign-return-request', [RequestHandleController::class, 'assignReturnRequest']);
    // handle return request
    Route::post('/handle-return-request', [RequestHandleController::class, 'handleReturnRequest']);
    Route::get('export-request', [ExportController::class, 'ExportRequest']);
});
// Mod route
Route::middleware(['auth','isMod'])->group(function() {
    Route::get('/moderator', [PageController::class, 'modRequestManager'])->name('moderator');
    // get all request
    Route::get('/all-request', [RequestController::class, 'allRequests']);
    // get complete request
    Route::get('/completed-request', [RequestController::class, 'completedRequest']);
    // get extend and return request
    Route::get('/extend-return-request', [RequestController::class, 'extendReturnRequests']);
    //handle request
    Route::get('/handle-request', [RequestController::class, 'handleRequest']);
    Route::post('/handle-request', [RequestHandleController::class, 'handleRequest']);
    Route::post('/return-request', [RequestHandleController::class, 'returnRequest']);
    Route::post('/extend-request', [RequestHandleController::class, 'extendRequest']);
    //user management
    Route::get('user-management', [PageController::class, 'userManagement'])->name('userManagement');

    //add user
    Route::post('/add-user', [UserController::class, 'add']);
    //delete user
    Route::post('/delete-user', [UserController::class, 'delete']);

    //device Manager route
    Route::get('assets', [DeviceManagePageController::class, 'getAssets'])->name('assets');
    Route::get('assets/add', [DeviceManagePageController::class, 'addAssets'])->name('assets.create');
    Route::get('asset/update/{id}', [DeviceManagePageController::class, 'getAssetById'])->name('assets.update');
    Route::get('asset/deploy/{id}', [DeviceManagePageController::class, 'assetsDeploy'])->name('assets.deploy');
    Route::get('asset/clone/{id}', [DeviceManagePageController::class, 'assetsClone'])->name('assets.clone');
    Route::get('asset/logs/{id}', [DeviceManagePageController::class, 'assetLogs'])->name('assets.logs');
    Route::get('assets/get', [AssetsController::class, 'getAssets'])->name('assets.get');
    Route::post('asset-insert', [AssetsController::class, 'insert']);
    Route::post('asset-update', [AssetsController::class, 'update']);
    Route::post('asset-delete', [AssetsController::class, 'delete']);
    Route::get('asset-download/{id}', [FileDownloadController::class, 'downloadAssetAttachment'])->name('assets.download');
    Route::post('assets-deploy', [AssetsController::class, 'deploy']);
    Route::post('assets-recall', [AssetsController::class, 'recall']);
    Route::post('assets-recall-cancel', [AssetsController::class, 'recallCancel']);

    //Device Manage
    Route::get('category', [DeviceManagePageController::class, 'getCategories'])->name('categories');
    Route::get('category/add', [DeviceManagePageController::class, 'addCategories'])->name('categories.add');
    Route::get('category/update/{id}', [DeviceManagePageController::class, 'getCategoryById'])->name('categories.update');
    Route::get('category/get', [CategoryController::class, 'getCategory'])->name('categories.get');
    Route::post('category-create', [CategoryController::class, 'insert']);
    Route::post('category-update', [CategoryController::class, 'update']);
    Route::post('category-delete', [CategoryController::class, 'delete']);

    Route::get('manufacturers', [DeviceManagePageController::class, 'getManufacturers'])->name('manufacturers');
    Route::get('manufacturer/add', [DeviceManagePageController::class, 'addManufacturer'])->name('manufacturers.add');
    Route::get('manufacturer/update/{id}', [DeviceManagePageController::class, 'getManufacturerById'])->name('manufacturers.update');
    Route::get('manufacturers/get', [ManufacturerController::class, 'getManufacturers'])->name('manufacturers.get');
    Route::post('manufacturer-create', [ManufacturerController::class, 'insert']);
    Route::post('manufacturer-update', [ManufacturerController::class, 'update']);
    Route::post('manufacturer-delete', [ManufacturerController::class, 'delete']);

    Route::get('models', [DeviceManagePageController::class, 'getModels'])->name('models');
    Route::get('model/add', [DeviceManagePageController::class, 'addModel'])->name('models.add');
    Route::get('model/update/{id}', [DeviceManagePageController::class, 'getModelById'])->name('models.update');
    Route::get('models/get', [ModelController::class, 'getModels'])->name('models.get');
    Route::post('model-create', [ModelController::class, 'insert']);
    Route::post('model-update', [ModelController::class, 'update']);
    Route::post('model-delete', [ModelController::class, 'delete']);

    Route::get('suppliers', [DeviceManagePageController::class, 'getSuppliers'])->name('Suppliers');
    Route::get('supplier/add', [DeviceManagePageController::class, 'addSupplier'])->name('Suppliers.add');
    Route::get('supplier/update/{id}', [DeviceManagePageController::class, 'getSupplierById'])->name('Suppliers.update');
    Route::get('suppliers/get', [SupplierController::class, 'getSuppliers'])->name('Suppliers.get');
    Route::post('supplier-create', [SupplierController::class, 'insert']);
    Route::post('supplier-update', [SupplierController::class, 'update']);
    Route::post('supplier-delete', [SupplierController::class, 'delete']);

    Route::get('maintenances', [DeviceManagePageController::class, 'getMaintenances'])->name('maintenances');
    Route::get('asset/maintenance/{id}', [DeviceManagePageController::class, 'getMaintenanceById'])->name('maintenances.update');
    Route::get('asset/maintenance/logs/{id}', [DeviceManagePageController::class, 'maintenanceLogs'])->name('maintenances.logs');
    Route::get('asset/maintenance/logs/detail/{id}', [DeviceManagePageController::class, 'maintenanceLogDetail'])->name('maintenances.logs');
    Route::get('maintenance-download/{id}', [FileDownloadController::class, 'downloadMaintenanceachment'])->name('maintenances.download');
    Route::get('maintenances/get', [MaintenancesController::class, 'getMaintenances'])->name('maintenances.get');
    Route::post('maintenance-setup', [MaintenancesController::class, 'mainternanceSetup']);

    Route::get('checkouts', [DeviceManagePageController::class, 'getCheckouts'])->name('checkouts');
    Route::get('checkouts/get', [AssetRequestablesController::class, 'checkoutList']);
    Route::get('checkouts/detail/{id}', [DeviceManagePageController::class, 'getCheckoutById']);
    Route::post('checkout-asset', [AssetRequestablesController::class, 'checkoutAsset']);

    Route::get('licenses', [DeviceManagePageController::class, 'getLicenses'])->name('licenses');
    Route::get('licenses/add', [DeviceManagePageController::class, 'addLicenses'])->name('licenses.create');
    Route::get('licenses/update/{id}', [DeviceManagePageController::class, 'getLicensesById'])->name('licenses.update');
    Route::get('licenses/deploy/{id}', [DeviceManagePageController::class, 'LicensesDeploy'])->name('licenses.deploy');
    Route::get('licenses/get', [LicensesController::class, 'getLicenses'])->name('Licenses.get');
    Route::post('licenses-insert', [LicensesController::class, 'insert']);
    Route::post('licenses-update', [LicensesController::class, 'update']);
    Route::post('licenses-delete', [LicensesController::class, 'delete']);
    Route::post('license-check', [LicensesController::class, 'checkDeployLicense']);
    Route::post('license-deploy', [LicensesController::class, 'deployLicense']);
    Route::get('licenses/deploy/{id}/get', [LicensesController::class, 'licenseDeployed']);
    Route::post('licenses/deploy/delete', [LicensesController::class, 'deployDelete']);
    Route::get('license-download/{id}', [FileDownloadController::class, 'downloadLicenseAttachment'])->name('assets.download');

    Route::get('export-request', [ExportController::class, 'ExportRequest']);
});


Route::get('/mailable', function () {
    $request = App\Models\MdRequest::with('department','requester','handler','files','type','sub_handler')->get();
    // dd($request);
    $mail = new App\Notifications\PurchaseDateMail($request);
    // $mail = new App\Notifications\AssignRequestInform($request);
    return $mail->toMail('test@example.com');
});

Route::get('ldap-test', function(HttpRequest $request){
    $user = Adldap::search()->where('uid', '=', 'thientt')->first();
    return response()->json($user);
});