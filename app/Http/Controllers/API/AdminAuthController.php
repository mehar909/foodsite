<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|confirmed'
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
