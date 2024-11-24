<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        Department::create([
            'code' => 'IT',
            'description' => 'Phòng Công nghệ thông tin',
        ]);

        // Kiểm tra xem phòng ban HR đã tồn tại chưa
        if (!Department::where('code', 'HR')->exists()) {
            Department::create([
                'code' => 'HR',
                'description' => 'Phòng Nhân sự',
            ]);
        }
    }
}
