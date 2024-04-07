<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $drinkProducts = Products::where('status', 1)
            ->where('unit', 'Cup')
            ->take(3)
            ->get();
        foreach ($drinkProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 1)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }
        $foodProducts = Products::where('status', 1)
            ->where('unit', 'Piece/Pack')
            ->take(3)
            ->get();
        foreach ($foodProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 4)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }
        return view('home', compact('drinkProducts', 'foodProducts'));
    }

    public function testMail()
    {
        $pdf_path = storage_path('app/public/receipt/receipt_id_6.pdf');
        $name = 'Nguyen Ngoc Duc';
        Mail::send('emails.receiptmail', compact('name'), function ($email) use($name, $pdf_path) {
            $email->subject('Receipt Info');
            $email->to('nguyenngocduc260504@gmail.com', $name);
            $email->attach($pdf_path);
        });
    }

    public function mail(){
        return view('emails.receiptmail');
    }
}
