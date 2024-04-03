<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\auth\admin\CategoryController;
use App\Http\Controllers\auth\admin\ProductController;
use App\Http\Controllers\auth\admin\StaffManagementController;
use App\Http\Controllers\auth\bartender\PrepareOrderController;
use App\Http\Controllers\auth\bartender\ProductStockController;
use App\Http\Controllers\auth\seller\OrderManageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductDetailsController;
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

Auth::routes();

Route::GET('/home', [HomeController::class, 'index'])->name('home')->withoutMiddleware(['auth']);

Route::GET('/menu', [MenuController::class, 'index'])->name('menu')->withoutMiddleware(['auth']);

Route::GET('/about', [AboutController::class, 'index'])->name('about')->withoutMiddleware(['auth']);

Route::GET('/product-details-{id}', [ProductDetailsController::class, 'index'])->name('productDetails')->withoutMiddleware(['auth']);

Route::GET('/get-product-food-{id}', [MenuController::class, 'getProductfood'])->name('getProductfood')->withoutMiddleware(['auth']);

Route::GET('/get-product-drink-{id}', [MenuController::class, 'getProductdrink'])->name('getProductdrink')->withoutMiddleware(['auth']);

Route::POST('/add-to-cart', [CartController::class, 'addToCart'])->withoutMiddleware(['auth']);

Route::GET('/cart', [CartController::class, 'showCart'])->name('cart')->withoutMiddleware(['auth']);

Route::GET('/get-user-cart', [CartController::class, 'checkAuthCart'])->withoutMiddleware(['auth']);

Route::DELETE('/remove-form-cart-{productIdAndSizeId}', [CartController::class, 'removeFromCart'])->withoutMiddleware(['auth']);

Route::POST('/change-quantity-product-cart-{productIdAndSizeId}-{quantity}', [CartController::class, 'changeQuantity'])->withoutMiddleware(['auth']);



Route::GROUP(['middleware'=>'isAdmin','prefix' => 'manage'], function () {

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

    Route::GROUP(['prefix' => 'categories'], function () {

        Route::GET('/get-category', [CategoryController::class, 'getCategories'])->name('admin.getCategories');

        Route::GET('/get-data-category', [CategoryController::class, 'getDataCategories'])->name('admin.getDataCategories');

        Route::GET('/get-data-products-{category}', [CategoryController::class, 'getDataProduct'])->name('admin.category.getDataProduct');

        Route::POST('/create-category', [CategoryController::class, 'createCategory'])->name('admin.createCategory');
    });
});


Route::GROUP(['prefix' => 'seller'], function () {

    Route::GROUP(['prefix' => 'orders'], function () {

        Route::GET('/orders-manage', [OrderManageController::class, 'getOrderManage'])->name('seller.orderManage');

        Route::GET('/get-data-product-size-{id}', [OrderManageController::class, 'getDataProductSize'])->name('seller.getDataProductSize');

        Route::GET('/generate-table-id', [OrderManageController::class, 'generateTableId'])->name('seller.generateTableId');

        Route::POST('/create-order', [OrderManageController::class, 'createOrder'])->name('seller.createOrder');

        Route::GET('/get-data-order-inprogress', [OrderManageController::class, 'getOrderListData']);

        Route::GET('/get-all-data-products', [OrderManageController::class, 'getDataProducts']);
    });
});

Route::GROUP(['prefix' => 'bartender'], function () {

    Route::GROUP(['prefix' => 'orders'], function () {

        Route::GET('/get-data-order-pending', [PrepareOrderController::class, 'getOrderPending']);

        Route::GET('/recrive-order', [PrepareOrderController::class, 'getReceiveOrder'])->name('bartender.getReceiveOrder');

        Route::GET('/get-data-order-details-{id}', [PrepareOrderController::class, 'getDataOrderDetails']);

        Route::GET('/check-order-inprogress', [PrepareOrderController::class, 'checkOrderInprocessByBartender']);

        Route::POST('/change-status-order-to-ready-{id}', [PrepareOrderController::class, 'changeOrderToReadyStatus']);
    });

    Route::GROUP(['prefix' => 'product'], function () {
        Route::GET('/get-product-stock', [ProductStockController::class, 'getProductStock'])->name('bartender.getProductStock');

        Route::GET('/get-data-products', [ProductStockController::class, 'getDataProduct']);

        Route::POST('/change-status-instock-{id}', [ProductStockController::class, 'changeStatusInstock']);
    });
});
