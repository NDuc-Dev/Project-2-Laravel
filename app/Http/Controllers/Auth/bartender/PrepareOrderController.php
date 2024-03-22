<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Orders;
use Illuminate\Http\Request;

class PrepareOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:bartender,bartender');
    }

    public function getOrderPending(){
        $data = Orders::where('order_status', 0)->get();
        return response()->json(['data' => $data]);
    }

    public function getReceiveOrder()
    {
        return view('auth.bartender.ReceiveOrder');
    }

    public function getDataOrderDetails($id){
        $data = OrderDetails::where('order_id', $id)->get();
        return response()->json(['data' => $data]);
    }
}
