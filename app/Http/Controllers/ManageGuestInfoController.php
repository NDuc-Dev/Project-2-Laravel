<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageGuestInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:guest,guest');
    }

    public function Index()
    {
        $user = Auth::user();
        return view('manageuserinfo', compact('user'));
    }

    public function updateUserInfo(Request $request)
    {
        $id = $request->input('user_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $user = Users::find($id);
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->delivery_address = $address;
        $user->save();
        return response()->json(['success'=>true, 'message'=> "Update info success fully"]);
    }
}
