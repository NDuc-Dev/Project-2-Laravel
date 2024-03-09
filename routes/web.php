<?php

use App\Http\Controllers\auth\admin\ProductController;
use App\Http\Controllers\auth\admin\StaffManagementController;
use App\Http\Controllers\auth\admin\AdminController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'manage'], function () {

    Route::group(['prefix' => 'staffs'], function () {

        Route::GET('/staff-management', [StaffManagementController::class, 'getStaffManagement'])->name('admin.staffManagement');

        Route::POST('/check-dupplicate-user-name', [StaffManagementController::class, 'checkUserName'])->name('admin.checkUName');

        Route::POST('/reset-password-{id}', [StaffManagementController::class, 'resetPassword'])->name('admin.resetPassword');

        Route::POST('/post-create-staff-account', [StaffManagementController::class, 'createStaff'])->name('admin.postCreateStaffs');

        Route::GET('/update-staff-{id}', [StaffManagementController::class, 'getUpdateStaff'])->name('admin.getUpdateStaff');

        Route::PUT('/put-update-staff-{id}', [StaffManagementController::class, 'putUpdateStaff'])->name('admin.putUpdateStaff');

        Route::match(['GET', 'POST'], '/change-status-{id}', [StaffManagementController::class, 'changeStatus'])->name('admin.changeStatus');
    });

    Route::group(['prefix' => 'products'], function () {

        Route::GET('/product-cagtegories-management', [ProductController::class, 'getProduct'])->name('admin.productManagement');

        Route::POST('/create-product', [ProductController::class, 'createProducts'])->name('admin.createProduct');

        Route::POST('/change-status-{id}', [ProductController::class, 'changeStatus'])->name('admin.changeStatusProduct');

        Route::GET('/update-product-{id}', [ProductController::class, 'getUpdateProduct'])->name('admin.getupdateProduct');

        Route::PUT('/put-update-product-{id}', [ProductController::class, 'putUpdateProduct'])->name('admin.putUpdateProduct');
    });
})->middleware('checRole, seller');


Route::group(['prefix' => 'seller'], function () {

    Route::group(['prefix' => 'orders'], function () {

        Route::get('/orders-manage', [OrderManageController::class, 'getOrderManage'])->name('seller.orderManage');

        Route::get('/get-data-product-size', [OrderManageController::class, 'getDataProductSize'])->name('seller.getDataProductSize');

    });
})->middleware('checRole, seller');
