<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Đăng ký người dùng mới (nhân viên)
    public function register(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => 1, // Đảm bảo tài khoản hoạt động
        ]);

        // Trả về thông tin người dùng và token
        return response()->json([
            'user' => $user,
            'message' => 'Tạo tài khoản thành công',
        ], 201);
    }

    // Đăng nhập và nhận token
    public function login(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Kiểm tra xác thực người dùng
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Tạo token Passport
            $token = $user->createToken('YourAppName')->accessToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }

        // Nếu thông tin đăng nhập không đúng
        return response()->json(['message' => 'Thông tin đăng nhập không chính xác'], 401);
    }
}
