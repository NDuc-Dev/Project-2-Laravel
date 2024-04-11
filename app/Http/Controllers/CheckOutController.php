<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Sizes;
use FPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CheckOutController extends Controller
{
    public function Index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);
            if ($cartItems == null) {
                return redirect('home');
            }

            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);

                $product = Products::find($productId);
                $size = Sizes::find($sizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $cartItems[$productIdAndSizeId]['product'] = $product;
                $cartItems[$productIdAndSizeId]['size'] = $size;
                $cartItems[$productIdAndSizeId]['productSize'] = $productSize;
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
            }
        } else {
            $cartItems = Session::get('guest_cart', []);
            if ($cartItems == null) {
                return redirect('home');
            }
            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $product = Products::find($productId);
                $size = Sizes::find($sizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');;
                $cartItems[$productIdAndSizeId]['product'] = $product;
                $cartItems[$productIdAndSizeId]['size'] = $size;
                $cartItems[$productIdAndSizeId]['productSize'] = $productSize;
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
            }
        }
        return view('CheckOut', ['cartItems' => $cartItems]);
    }

    public function changePaymentStatus(Request $request)
    {
        $order = Orders::find($request->input('orderId'));
        $order->payment_status = 1;
        $order->save();
    }

    public function GetTotalPay()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);
            $totalAmt = 0;
            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
                $total = $productSize * $item['quantity'];
                $totalAmt += $total;
            }
        } else {
            $cartItems = Session::get('guest_cart', []);
            $totalAmt = 0;
            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
                $total = $productSize * $item['quantity'];
                $totalAmt += $total;
            }
        }
        return response()->json(['status' => 200, 'totalAmt' => $totalAmt]);
    }

    public function vnpay_payment(Request $request)
    {
        $deliveryInfo = $request->input('name') . "-" . $request->input('phone') . "-" . $request->input('address');
        $productArray = [];
        $order_date = date('Y-m-d H:i:s');
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);
            $totalAmt = 0;
            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $productSizeprice = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $productSizeId = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('product_size_id');
                $total = $productSizeprice * $item['quantity'];
                $productname = Products::where('product_id', $productId)->value('product_name');
                $size_name = Sizes::where('size_id', $sizeId)->value('size_name');
                if ($sizeId != 4) {
                    $product_size_name = $productname . " " . $size_name;
                } else {
                    $product_size_name = $productname;
                }
                $productArray[] = [
                    'product_name' => $product_size_name,
                    'product_size_id' => $productSizeId,
                    'quantity' => $item['quantity'],
                    'amount' => $total,
                ];
                $count = count($productArray);
                $totalAmt += $total;
            }
            $count = count($productArray);
            $order = Orders::create([
                'order_date' => $order_date,
                'order_type' => 0,
                'order_by' => Auth::id(),
                'total' => $totalAmt,
                'table_id' => 0,
                'order_status' => 1,
                'delivery_address' => $deliveryInfo
            ]);
            $order_id = $order->order_id;
            $order->receipt_path = asset($this->CreateInvoice($count, $productArray, $order_date, $order_id, $totalAmt));
            $order->save();
            foreach ($productArray as $product) {
                OrderDetails::create([
                    'order_id' => $order_id,
                    'product_size_id' => $product['product_size_id'],
                    'product_name' => $product['product_name'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount']
                ]);
            }
        } else {
            $cartItems = Session::get('guest_cart', []);
            $totalAmt = 0;
            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $productSizeprice = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $productSizeId = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('product_size_id');
                $total = $productSizeprice * $item['quantity'];
                $productname = Products::where('product_id', $productId)->value('product_name');
                $size_name = Sizes::where('size_id', $sizeId)->value('size_name');
                if ($sizeId != 4) {
                    $product_size_name = $productname . " " . $size_name;
                } else {
                    $product_size_name = $productname;
                }
                $productArray[] = [
                    'product_name' => $product_size_name,
                    'product_size_id' => $productSizeId,
                    'quantity' => $item['quantity'],
                    'amount' => $total,
                ];
                $count = count($productArray);
                $totalAmt += $total;
            }
            $order = Orders::create([
                'order_date' => $order_date,
                'order_type' => 0,
                'order_by' => 0,
                'total' => $totalAmt,
                'table_id' => 0,
                'order_status' => 0,
                'delivery_address' => $deliveryInfo
            ]);
            $order_id = $order->order_id;
            $order->receipt_path = asset($this->CreateInvoice($count, $productArray, $order_date, $order_id, $totalAmt));
            $order->save();
            foreach ($productArray as $product) {
                OrderDetails::create([
                    'order_id' => $order_id,
                    'product_size_id' => $product['product_size_id'],
                    'product_name' => $product['product_name'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount']
                ]);
            }
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/checkout";
        $vnp_TmnCode = "0JF2F1I2";
        $vnp_HashSecret = "JLZQHLJNLEXCEZKMPXIGNLCNZKYTQFTO";

        $vnp_TxnRef = $order_id;
        $vnp_OrderInfo = 'Thanh Toán Test';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $totalAmt * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];



        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    public function clearCart()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Session::forget("user_cart_$userId");
        } else {
            Session::forget("guest_cart");
        }
        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function CreateInvoice($count, $orderProducts, $orderDate, $orderCode, $Total)
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
        if (Auth::check()) {
            $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Guest Name :" . Auth::user()->name), 0, 'C', false);
        } else {
            $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Guest Name : Retail customer"), 0, 'C', false);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper("Order Id: " . $orderCode)), 0, 'C', false);
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
        $publicPath = public_path('receipt');
        File::makeDirectory($publicPath, $mode = 0777, true, true);
        $pdfContent = $pdf->Output('S');
        $fileName = 'receipt_' . 'id_' . $orderCode . '.pdf';
        Storage::disk('public')->put('receipt/' . $fileName, $pdfContent);
        return Storage::url('receipt/' . $fileName);
    }

    public function sendEmail(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Orders::find($order_id);
        if ($order) {
            $pdf_path = public_path($order->receipt_path);
            $name = explode("-", $order->delivery_address);
            $guest_email = $order->guest_email;
            if ($guest_email != null) {
                Mail::send('emails.receiptmail', compact('name'), function ($email) use ($name, $pdf_path, $guest_email) {
                    $email->subject('Receipt Info');
                    $email->to($guest_email, $name);
                    $email->attach($pdf_path);
                });
            } else {
            }
        }
    }
}
