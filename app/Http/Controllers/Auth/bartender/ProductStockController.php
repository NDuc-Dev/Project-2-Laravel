<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:bartender,bartender');
    }

    public function getProductStock()
    {
        return view('auth.bartender.productstock');
    }

    public function getDataProduct()
    {
        $data = Products::all();
        return response()->json(['data' => $data]);
    }

    public function changeStatusInstock(Request $request)
    {
        DB::beginTransaction();
        $product_id = $request->input('product_id');

        $product = Products::find($product_id);
        $product->status_in_stock = ($product->status_in_stock == 1) ? 0 : 1;
        $product->save();
        try {
            $productAfter =  Products::find($product_id);
            $stock = $productAfter->status_in_stock;
            if ($stock == 0) {
                $productSizeId = ProductSizes::where('product_id', $product_id)->pluck('product_size_id');
                $orderIds = Orders::where('order_status', 0)->pluck('order_id');
                $products = [];
                foreach ($orderIds as $id) {
                    $products = array_merge($products, OrderDetails::where('order_id', $id)->get()->toArray());
                }
                foreach ($productSizeId as $psizeId) {
                    foreach ($products as $product) {
                        if ($product['product_size_id'] == $psizeId) {
                            $order = Orders::find($product['order_id']);
                            $order->order_status = -1;
                            $order->save();
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'messages' => "Change Status success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to change');
        }
    }
}
