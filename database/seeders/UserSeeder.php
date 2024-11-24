<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    public function run()
    {
        // Lấy ID của phòng ban HR
        $hrDepartment = Department::where('code', 'HR')->first();

        // Tạo người dùng thuộc phòng ban HR
        DB::table('users')->insert([
            [
                'employee_code' => 'HR001',  // Mã nhân viên
                'name' => 'ADMIN',    // Tên người dùng
                'email' => 'admin@gmail.com', // Email người dùng
                'password' => bcrypt('admin123'), // Mật khẩu đã mã hóa
                'is_active' => true,           // Người dùng đang hoạt động
                'created_by' => 1,             // ID của người tạo tài khoản (nếu có thể)
                'department_id' => $hrDepartment->id, // Gán phòng ban HR

            ],
            [
                'employee_code' => 'HR002',  // Mã nhân viên
                'name' => 'Phan Hữu Toại',    // Tên người dùng
                'email' => 'phantoai01@gmail.com', // Email người dùng
                'password' => bcrypt('admin123'), // Mật khẩu đã mã hóa
                'is_active' => true,           // Người dùng đang hoạt động
                'created_by' => 1,             // ID của người tạo tài khoản (nếu có thể)
                'department_id' => $hrDepartment->id, // Gán phòng ban HR

            ],
        ]);
    }
}
