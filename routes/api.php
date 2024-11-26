<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

// Đăng ký tài khoản nhân viên mới
Route::post('/register', [AuthController::class, 'register']);

// Đăng nhập và nhận token
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth')->group(function () {
//     Route::middleware(['role:user'])->group(function () {
//         // Route để hiển thị danh sách đơn hàng của tài khoản người dùng
//         Route::get('/tai-khoan/don-hang', [UserController::class, 'orders'])->name('account.orders');
//         // Route để hiển thị chi tiết của một đơn hàng cụ thể theo ID
//         Route::get('/tai-khoan/don-hang/{id}', [UserController::class, 'orderDetail'])->name('account.orders.detail');
//         // Route để hiển thị trang thay đổi mật khẩu của tài khoản người dùng
//         Route::get('/account/changepassword', [UserController::class, 'changepassword'])->name('account.changepassword');
//         // Route để xử lý yêu cầu cập nhật mật khẩu của tài khoản người dùng
//         Route::post('/account/update-password', [UserController::class, 'update_password'])->name('account.update.password');
//     });
// });