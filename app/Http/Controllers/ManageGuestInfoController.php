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
}
