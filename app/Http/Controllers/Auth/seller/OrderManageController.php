<?php

namespace App\Http\Controllers\auth\seller;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Tables;
use FPDF;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Prompts\Table;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:seller,seller');
    }

    public function getOrderManage()
    {
        return view('auth.seller.ordermanage');
    }

    public function getDataProductsActive()
    {
        $products = Products::where('status', 1)
            ->where('status_in_stock', 1)
            ->get();
        return response()->json(['products' => $products]);
    }

    public function getOrdersError()
    {
        $data = Orders::where('order_status', '=', 0)->get();
        return response()->json(['data' => $data]);
    }

    public function getOrdersReady()
    {
        $data = Orders::where('order_status', '=', 3)->get();
        return response()->json(['data' => $data]);
    }

    public function getOrdersDelivering()
    {
        $data = Orders::where('order_status', '=', 4)->get();
        return response()->json(['data' => $data]);
    }

    public function deliveryOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $order_id = $request->input('order_id');
            $delivery_code = $request->input('delivery_code');
            if (is_null($order_id) || is_null($delivery_code)) {
                throw new \Exception("Order ID and delivery code cannot be null.");
            }

            Orders::where('id', $order_id)->update([
                'order_status' => 4,
                'delivery_code' => $delivery_code,
            ]);

            DB::commit();

            return response()->json(['success' => true, "status" => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, "status" => 500, 'message' => $e->getMessage()]);
        }
    }

    public function completeOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $order_id = $request->input('order_id');
            Orders::where('order_id', $order_id)->update([
                'order_status' => 5,
                'success_at' => Carbon::now(),
            ]);
            $order = Orders::find($order_id);
            if ($order->order_type != 0) {
                $table_id = $order->table_id;
                $table = Tables::find($table_id);
                $table->table_status = 1;
                $table->save();
            }
            DB::commit();

            return response()->json(['success' => true, "status" => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, "status" => 500, 'message' => $e->getMessage()]);
        }
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

    public function generateTableId()
    {
        $randomId = mt_rand(1, 40);

        $table = Tables::where('table_id', $randomId)
            ->where('table_status', 1)
            ->first();

        while ($table && $randomId == 0) {
            $randomId = mt_rand(1, 40);
            $table = Tables::where('table_id', $randomId)
                ->where('table_status', 1)
                ->first();
        }

        return $randomId;
    }

    public function createOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'orderDate' => 'required|date_format:Y-m-d H:i:s',
                'products' => 'required|array',
                'orderTable' => 'required|numeric',
                'formattedTime' => 'required'
            ]);
            $products = $request->products;
            $count = count($products);
            foreach ($products as $product) {
                Validator::make($product, [
                    'quantity' => 'required|integer|between:1,10',
                ])->validate();
            }
            $total_amount = 0;
            foreach ($products as $product) {
                $total_amount += $product['amount'];
            }

            $order = Orders::create([
                'order_date' => $request->input('orderDate'),
                'order_type' => 1,
                'order_by' => Auth::id(),
                'total' => $total_amount,
                'table_id' => $request->input('orderTable'),
                'order_status' => 1,
                'payment_status' => 1,

            ]);
            $order_id = $order->order_id;
            $order->receipt_path = asset($this->CreateInvoice($count, $products, $request->input('formattedTime'), $request->input('orderTable'), $order_id, $total_amount));
            $order->save();
            DB::table('tables')
                ->where('table_id', $request->input('orderTable'))
                ->update(['table_status' => 0]);
            foreach ($products as $product) {
                OrderDetails::create([
                    'order_id' => $order_id,
                    'product_size_id' => $product['product_size_id'],
                    'product_name' => $product['product_name'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount']
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'receipt_path' => $order->receipt_path, 'messages' => "Create order success"]);
    }

    public function CreateInvoice($count, $orderProducts, $orderDate, $orderTable, $orderCode, $Total)
    {
        $height = $count * 5;
        $pdf = new FPDF('P', 'mm', array(80, 150 + $height));
        $pdf->SetMargins(4, 10, 4);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("NDC COFFEE")), 0, 'C', false);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Add: 4th floor, VTC Online Building, Tam Trinh Street, Hai Ba Trung, HN"), 0, 'C', false);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Tel: (84) 965709059"), 0, 'C', false);
        $pdf->Ln(1);
        $pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "------------------------------------------------------"), 0, 0, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("** RECEIPT **")), 0, 'C', false);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Date: " . $orderDate), 0, 'C', false);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Order Staff :" . Auth::user()->name), 0, 'C', false);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("Order Id: " . $orderCode)), 0, 'C', false);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("Table: " . $orderTable)), 0, 'C', false);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Ln(1);
        $pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $pdf->Ln(4);
        $pdf->Cell(25, 5, iconv("UTF-8", "ISO-8859-1", "Products"), 0, 0, 'L');
        $pdf->Cell(25, 5, iconv("UTF-8", "ISO-8859-1", "Quantity"), 0, 0, 'C');
        $pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "Amount"), 0, 0, 'R');
        $pdf->Ln(3);
        $pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $pdf->Ln(3);
        foreach ($orderProducts as $product) {
            $pdf->Ln(2);
            $pdf->Cell(20, 4, iconv("UTF-8", "ISO-8859-1", $product['product_name']), 0, 0, 'L');
            $pdf->Cell(33, 4, iconv("UTF-8", "ISO-8859-1", $product['quantity']), 0, 0, 'C');
            $pdf->Cell(0, 4, iconv("UTF-8", "ISO-8859-1", number_format($product['amount'], 0, '.', '.') . " VND"), 0, 0, 'R');
            $pdf->Ln(4);
        }
        $pdf->Ln(5);
        $pdf->Cell(24, 5, iconv("UTF-8", "ISO-8859-1", ""), 0, 0, 'C');
        $pdf->Cell(15, 5, iconv("UTF-8", "ISO-8859-1", "Sub-Total"), 0, 0, 'C');
        $pdf->Cell(46, 5, iconv("UTF-8", "ISO-8859-1", number_format($Total, 0, '.', '.') . " VND"), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "---------------------"), 0, 0, 'R');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(24, 5, iconv("UTF-8", "ISO-8859-1", ""), 0, 0, 'C');
        $pdf->Cell(15, 5, iconv("UTF-8", "ISO-8859-1", "TOTAL"), 0, 0, 'C');
        $pdf->Cell(44, 5, iconv("UTF-8", "ISO-8859-1", number_format($Total, 0, '.', '.') . " VND"), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 9);

        $pdf->Ln(5);
        $pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "*** Thank you for using our service ***"), 0, 'C', false);
        $pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1", "** Share your comments with us **"), '', 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1", "serviceNDCCoffee@gmail.com "), '', 0, 'C');
        $pdf->Ln(9);
        $pdfContent = $pdf->Output('S');
        $fileName = 'receipt_' . 'id_' . $orderCode . '.pdf';
        Storage::disk('public')->put('receipt/' . $fileName, $pdfContent);
        return Storage::url('receipt/' . $fileName);
    }

    public function GetDetailsOrderError(Request $request)
    {
        $product_out_of_stock = Products::where('status_in_stock', 0)->select('product_id')->get()->toArray();
        $product_size_out_of_stock = [];
        foreach ($product_out_of_stock as $product) {
            $product_sizes = ProductSizes::where('product_id', $product)->select('product_size_id')->get()->toArray();
            $product_size_out_of_stock = array_merge($product_size_out_of_stock, $product_sizes);
        }
        $order_id = $request->input('order_id');
        $order = Orders::find($order_id);
        $products = OrderDetails::where('order_id', $order_id)->get();
        foreach ($products as $product) {
            $out_of_stock = false;
            foreach ($product_size_out_of_stock as $size) {
                if ($product->product_size_id == $size['product_size_id']) {
                    $out_of_stock = true;
                    break;
                }
            }
            $product->out_of_stock = $out_of_stock;
        }
        return response()->json(['success' => true, 'order' => $order, 'products' => $products, ' product_size_out_of_stock'=>  $product_size_out_of_stock, 'product_out_of_stock'=>$product_out_of_stock]);
    }

    public function getDataProducts()
    {
        $data = Products::all();
        return response()->json($data);
    }
}
