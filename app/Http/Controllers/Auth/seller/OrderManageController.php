<?php

namespace App\Http\Controllers\auth\seller;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Tables;
use App\PDF\InvoicePdf;
use FPDF;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
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
        $products = Products::where('status', 1)
            ->where('status_in_stock', 1)
            ->get();
        return view('auth.seller.ordermanage', compact('products'));
    }

    public function getOrderListData()
    {
        $data = Orders::where('order_status', '!=', 4)->get();
        return response()->json(['data' => $data]);
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
                'order_status' => 0,
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
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1", "customerserrviceNDCCoffee@gmail.com "), '', 0, 'C');
        $pdf->Ln(9);
        $pdfContent = $pdf->Output('S');
        $fileName = 'receipt_' . 'id_' . $orderCode . '.pdf';
        Storage::disk('public')->put('receipt/' . $fileName, $pdfContent);
        return Storage::url('receipt/' . $fileName);
    }

    public function getDataProducts(){
        $data = Products::all();
        return response()->json($data);
    }
}
