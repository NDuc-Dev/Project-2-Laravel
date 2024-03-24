<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrepareOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:bartender,bartender');
    }

    public function getOrderPending()
    {
        $data = Orders::where('order_status', 0)->get();
        return response()->json(['data' => $data]);
    }

    public function getReceiveOrder()
    {
        return view('auth.bartender.ReceiveOrder');
    }

    public function getDataOrderDetails($id)
    {
        $order = Orders::find($id);

        if ($order) {
            $priorOrders = Orders::where('order_date', '<', $order->order_date)
                ->where('order_status', 0)
                ->get();

            if ($priorOrders->isEmpty() || $priorOrders->contains($order)) {
                $order->order_status = 1;
                $order->prepared_by = Auth::id();
                $order->prepared_at = Carbon::now();
                $order->save();
                $data = OrderDetails::where('order_id', $id)->get();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'Please process orders in the correct order']);
            }
        }
    }

    public function checkOrderInprocessByBartender()
    {
        $orderInprogress = Orders::where('prepared_by', Auth::id())
            ->where('order_status', 1)
            ->get();

        if ($orderInprogress->isEmpty()) {
            return response()->json(['success' => false]);
        } else {
            foreach ($orderInprogress as $order) {
                $orderId = $order->order_id;
            }
            $order = Orders::find($orderId);
            $data = OrderDetails::where('order_id', $orderId)->get();
            return response()->json(['success' => true, 'data' => $data, 'orderId' => $orderId, 'order' => $order]);
        }
    }

    public function changeOrderToReadyStatus($id)
    {
        $order = Orders::find($id);
        $order->order_status = 2;
        $order->save();
        return response()->json(['success' => true]);
    }
}
