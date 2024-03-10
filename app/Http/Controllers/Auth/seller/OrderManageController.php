<?php

namespace App\Http\Controllers\auth\seller;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Tables;
use Illuminate\Http\Request;
use Laravel\Prompts\Table;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:seller,seller');
    }


    public function getOrderManage()
    {
        $products = Products::all();
        return view('auth.seller.ordermanage', compact('products'));
    }

    public function getDataProductSize(Request $request)
    {
        $product_id = $request->input('product_id');

        $productSize = ProductSizes::where('product_id', $product_id)->get();

        if ($productSize) {
            return response()->json(['success' => true, 'productSize' => $productSize]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product size not found']);
        }
    }

    public function generateUniqueOrderId()
    {
        $randomcodeId = mt_rand(000000, 999999);

        $order = Orders::where('order_id', $randomcodeId)->first();

        while ($order && $randomcodeId == 0) {
            $randomcodeId = mt_rand(000000, 999999);
            $order = Orders::where('order_id', $randomcodeId)->first();
        }

        return $randomcodeId;
    }

    public function generateTableId()
    {
        $randomId = mt_rand(0, 40);

        $table = Tables::where('table_id', $randomId)
            ->where('table_status', 1)
            ->first();

        while ($table && $randomId == 0) {
            $randomId = mt_rand(0, 40);
            $table = Tables::where('table_id', $randomId)
                ->where('table_status', 1)
                ->first();
        }

        return $randomId;
    }
}
