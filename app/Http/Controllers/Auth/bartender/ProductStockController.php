<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use App\Models\Products;
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

    public function changeStatusInstock($id)
    {
        DB::beginTransaction();

        $product = Products::find($id);

        try {
            $product->status_in_stock = ($product->status_in_stock == 1) ? 0 : 1;
            $product->save();
            DB::commit();
            return response()->json(['success' => true, 'messages' => "Change Status success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to change');
        }
    }
}
