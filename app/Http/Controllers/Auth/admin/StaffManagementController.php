<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffManagementController extends Controller
{

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('checkRole:admin,admin');
    }


    public function getStaffManagement()
    {
        return view('auth.admin.staffs.staffmanage');
    }

    public function getDataStaff()
    {
        $roles = ['bartender', 'seller'];
        $data = Users::whereIn('role', $roles)->get();
        return response()->json(['data' => $data]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255'],
        ]);
    }

    protected function createStaff(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            Users::create([
                'name' => $data['name'],
                'user_name' => $data['user_name'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 1,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Create User Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Create User failed']);
        }
    }

    public function getUpdateStaff($id)
    {
        $user = Users::find($id);
        if (!$user || $user->role = 'admin' || $user->role = 'guest') {
            return response()->json(['error' => 'User not found'], 404);
        } else {
        }
        return view('auth.admin.staffs.updateStaff', compact('user'));
    }

    public function putUpdateStaff(Request $request, $user_id)
    {
        
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required|string|minimumLetters|max:255',
                'email' => 'required|string|regexEmail|max:255',
                'phone' => 'required|string|regexPhone|max:255',
                'role' => 'required|selectRequired',
            ], [
                'name.required' => 'Vui lòng nhập tên.',
                'name.minimumLetters' => 'Tên không hợp lệ.',
                'email.required' => 'Vui lòng nhập email.',
            ]);

            $user = Users::findOrFail($user_id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->role = $request->input('role');

            $user->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User information updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => 'An error occurred while updating user information']);
        }
    }

    public function changeStatus($id)
    {

        try {
            DB::beginTransaction();

            $user = Users::find($id);
            $user->status = ($user->status == 1) ? 0 : 1;
            $user->save();
            DB::commit();
            return response()->json(['success' => true, 'messages' => "Change Status success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to change');
        }
    }

    public function checkExistInfo(Request $request)
    {
        $username = $request->input('username');
        $phone = $request->input('phone');
        $mail = $request->input('email');

        $user_name = Users::where('user_name', $username)->first();
        $phone_num = Users::where('phone', $phone)->first();
        $email = Users::where('email', $mail)->first();

        if ($user_name) {
            return response()->json(['exists' => true, 'message' => "User Name is already exist !"]);
        } else if ($phone_num) {
            return response()->json(['exists' => true, 'message' => "Phone Number is already exist !"]);
        } else if ($email) {
            return response()->json(['exists' => true, 'message' => "Email is already exist !"]);
        } else {
            return response()->json(['exists' => false]);
        }
    }


    public function resetPassword(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $password = Str::random(3);
            $password .= rand(100, 999);
            $newPassword = str_shuffle($password);

            $user = Users::find($id);
            $user->password = Hash::make($newPassword);
            $user->save();

            DB::commit();

            return response()->json(['success' => true, 'newPassword' => $newPassword]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'An error occurred while resetting password.'], 500);
        }
    }
}
