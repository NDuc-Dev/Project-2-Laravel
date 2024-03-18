<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckRole;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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

    public function getDataStaff(){
        $data = Users::where('role', '!=', 'admin')->get();
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
        DB::beginTransaction();

        $data = $request->all();
        try {
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
            // \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Create User failed']);

        }
    }

    public function getUpdateStaff($id)
    {
        $user = Users::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        } else {
        }
        return view('auth.admin.staffs.updateStaff', compact('user'));
    }

    public function putUpdateStaff(Request $request, $user_id)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validate request data
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

            // Find the user by ID
            $user = Users::findOrFail($user_id);

            // Update user data
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
        DB::beginTransaction();

        $user = Users::find($id);

        try {
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

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');

        $user = Users::where('user_name', $username)->first();

        // dd($user);

        if ($user) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }


    public function resetPassword(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // $newPassword = Str::random(6);

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
