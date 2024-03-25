<?php

use App\Http\Controllers\auth\admin\ProductController;
use App\Http\Controllers\auth\admin\StaffManagementController;
use App\Http\Controllers\auth\admin\AdminController;
use App\Http\Controllers\auth\bartender\PrepareOrderController;
use App\Http\Controllers\auth\bartender\ProductStockController;
use App\Http\Controllers\auth\seller\OrderManageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('loading_spinner');
});

Auth::routes();

Route::GET('/home', [HomeController::class, 'index'])->name('home');

Route::GROUP(['prefix' => 'manage'], function () {

    Route::GROUP(['prefix' => 'staffs'], function () {

        Route::GET('/get-data-staff', [StaffManagementController::class, 'getDataStaff'])->name('admin.getDataStaff');

        Route::GET('/staff-management', [StaffManagementController::class, 'getStaffManagement'])->name('admin.staffManagement');

        Route::POST('/check-dupplicate-user-name', [StaffManagementController::class, 'checkUserName'])->name('admin.checkUName');

        Route::POST('/reset-password-{id}', [StaffManagementController::class, 'resetPassword'])->name('admin.resetPassword');

        Route::POST('/post-create-staff-account', [StaffManagementController::class, 'createStaff'])->name('admin.postCreateStaffs');

        Route::GET('/update-staff-{id}', [StaffManagementController::class, 'getUpdateStaff'])->name('admin.getUpdateStaff');

        Route::PUT('/put-update-staff-{id}', [StaffManagementController::class, 'putUpdateStaff'])->name('admin.putUpdateStaff');

        Route::MATCH(['GET', 'POST'], '/change-status-{id}', [StaffManagementController::class, 'changeStatus'])->name('admin.changeStatus');
    });

    Route::GROUP(['prefix' => 'products'], function () {

        Route::GET('/get-data-products', [ProductController::class, 'getDataProduct'])->name('admin.getDataProduct');

        Route::GET('/product-cagtegories-management', [ProductController::class, 'getProduct'])->name('admin.productManagement');

        Route::POST('/create-product', [ProductController::class, 'createProducts'])->name('admin.createProduct');

        Route::POST('/change-status-{id}', [ProductController::class, 'changeStatus'])->name('admin.changeStatusProduct');

        Route::GET('/update-product-{id}', [ProductController::class, 'getUpdateProduct'])->name('admin.getupdateProduct');

        Route::PUT('/put-update-product-{id}', [ProductController::class, 'putUpdateProduct'])->name('admin.putUpdateProduct');
    });
})->middleware('checRole, seller');


Route::GROUP(['prefix' => 'seller'], function () {

    Route::GROUP(['prefix' => 'orders'], function () {

        Route::GET('/orders-manage', [OrderManageController::class, 'getOrderManage'])->name('seller.orderManage');

        Route::GET('/get-data-product-size-{id}', [OrderManageController::class, 'getDataProductSize'])->name('seller.getDataProductSize');

        Route::GET('/generate-table-id', [OrderManageController::class, 'generateTableId'])->name('seller.generateTableId');

        Route::POST('/create-order', [OrderManageController::class, 'createOrder'])->name('seller.createOrder');

        Route::GET('/get-data-order-inprogress', [OrderManageController::class, 'getOrderListData']);

        Route::GET('/get-all-data-products',[OrderManageController::class, 'getDataProducts']);

    });
})->middleware('checRole, seller');

Route::GROUP(['prefix' => 'bartender'], function () {

    Route::GROUP(['prefix' => 'orders'], function () {

        Route::GET('/get-data-order-pending', [PrepareOrderController::class, 'getOrderPending']);

        Route::GET('/recrive-order', [PrepareOrderController::class, 'getReceiveOrder'])->name('bartender.getReceiveOrder');

        Route::GET('/get-data-order-details-{id}',[PrepareOrderController::class, 'getDataOrderDetails']);

        Route::GET('/check-order-inprogress',[PrepareOrderController::class, 'checkOrderInprocessByBartender']);

        Route::POST('/change-status-order-to-ready-{id}',[PrepareOrderController::class,'changeOrderToReadyStatus']);

    });

    Route::GROUP(['prefix' => 'product'],function(){
        Route::GET('/get-product-stock', [ProductStockController::class, 'getProductStock'])->name('bartender.getProductStock');
    });
})->middleware('checRole, bartender');
