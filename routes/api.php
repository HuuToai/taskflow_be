<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\LinkController;

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });



    Route::get('/users/edit/{id}', [UserController::class, 'edit']);
    Route::get('/users/create', [UserController::class, 'getCreate']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);

    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/edit/{department}', [DepartmentController::class, 'edit']);
    Route::post('/departments/store', [DepartmentController::class, 'store']);
    Route::put('/departments/update/{id}', [DepartmentController::class, 'update']);
    // Route để khôi phục người dùng đã xóa
    Route::post('/users/restore/{id}', [UserController::class, 'restore']);

    // Route để xóa hoàn toàn người dùng
    Route::delete('/users/force-delete/{id}', [UserController::class, 'forceDelete']);

    // Route để lấy danh sách người dùng đã bị xóa
    Route::get('/users/trash', [UserController::class, 'trash']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users/register', [UserController::class, 'register']);

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'getGanttData']); // Thêm mới task
        Route::post('/', [TaskController::class, 'store']); // Thêm mới task
        Route::put('/{id}', [TaskController::class, 'update']); // Cập nhật task
        Route::delete('/{id}', [TaskController::class, 'destroy']); // Xóa task
    });

    Route::prefix('links')->group(function () {
        Route::post('/', [LinkController::class, 'store']); // Thêm mới link
        Route::put('/{id}', [LinkController::class, 'update']); // Cập nhật link
        Route::delete('/{id}', [LinkController::class, 'destroy']); // Xóa link
    });
});

// Đăng ký tài khoản nhân viên mới

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
