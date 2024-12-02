<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    // Đăng nhập và nhận token
    public function login(Request $request)
    {

        // Validate dữ liệu
        $request->validate([
            'email' => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => [
                'required',
                'string',
                'min:8',
                // 'regex:/[A-Z]/',
                // 'regex:/[a-z]/',
                // 'regex:/[0-9]/',
                // 'regex:/[@$!%*?&#]/',
            ],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.regex' => 'Email không hợp lệ. Vui lòng kiểm tra lại',
            'password.regex' => 'Mật khẩu phải bao gồm ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);
        $email = $request->email;
        $password = $request->password;
        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::user();
                // Kiểm tra trạng thái tài khoản
                if (!$user->is_active) {
                    return response()->json([
                        'response_code' => '403',
                        'status' => 'error',
                        'message' => 'Tài khoản đã bị khóa.',
                    ], 403);
                }
                if ($user->deleted_at !== null) {
                    return response()->json([
                        'response_code' => '403',
                        'status' => 'error',
                        'message' => 'Tài khoản đã Xóaaaaaaaa.',
                    ], 403);
                }
                $token = $user->createToken($user->email)->accessToken;
                $latestToken = $user->tokens()->latest()->first();
                // Trả về kết quả
                return response()->json([
                    'response_code' => '200',
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công',
                    'access_token' => $token,
                    'user_infor' => $user,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($latestToken->expires_at)->toDateTimeString(),
                ]);
            }

            // Xử lý đăng nhập thất bại
            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Email hoặc mật khẩu không đúng.',
            ], 401);
        } catch (\Exception $e) {
            // Ghi log lỗi và trả về phản hồi
            Log::error($e->getMessage());
            return response()->json([
                'response_code' => '500',
                'status' => 'error',
                'message' => 'Lỗi hệ thống, vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user(); // Lấy thông tin người dùng hiện tại

        // Thu hồi Access Token hiện tại
        $user->token()->revoke();

        return response()->json([
            'response_code' => '200',
            'status' => 'success',
            'message' => 'Đăng xuất thành công',
        ]);
    }


    public function refreshToken(Request $request)
    {
        $user = $request->user(); // Lấy thông tin người dùng hiện tại

        // Thu hồi token cũ
        $user->token()->revoke();

        // Tạo token mới
        $token = $user->createToken($user->email)->accessToken;
        return response()->json([
            'response_code' => '200',
            'status' => 'success',
            'message' => 'Làm mới token thành công',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(15)->toDateTimeString(),
        ]);
    }

    public function checkToken()
    {
        if (Auth::guard('api')->check()) {
            return response()->json([
                'response_code' => '200',
                'status' => 'success',
                'message' => 'Token hợp lệ',
                'user' => Auth::guard('api')->user(),
            ]);
        } else {
            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Token không hợp lệ',
            ], 401);
        }
    }
}
