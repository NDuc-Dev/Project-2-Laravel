<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
    public function Index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);

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

            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $product = Products::find($productId);
                $size = Sizes::find($sizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                ;
                $cartItems[$productIdAndSizeId]['product'] = $product;
                $cartItems[$productIdAndSizeId]['size'] = $size;
                $cartItems[$productIdAndSizeId]['productSize'] = $productSize;
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
            }
        }
        return view('CheckOut', ['cartItems' => $cartItems]);
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
        $totalAmt = $request->input('totalAmt');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/checkout";
        $vnp_TmnCode = "FMEADEWD";//Mã website tại VNPAY 
        $vnp_HashSecret = "DZQHAIYPMMVOHYIIQTJZKBROQFTOFUBT"; //Chuỗi bí mật

        $vnp_TxnRef = '12556';
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
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00'
            ,
            'message' => 'success'
            ,
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
    }
}
