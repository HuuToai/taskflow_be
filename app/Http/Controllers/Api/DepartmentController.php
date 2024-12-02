<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        try {
            $departments = Department::all();
            return response()->json($departments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getCreate()
    {
        $departments = DB::table("departments")
            ->select("id as value", "description as label")
            ->get();

        return response()->json([
            "departments" => $departments
        ], 200);
    }

    // Đăng ký người dùng mới (nhân viên)
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:departments,code',
            'description' => 'required|string|max:255',
        ], [
            'code.required' => 'Vui lòng nhập mã phòng ban.',
            'code.unique' => 'Mã phòng ban đã tồn tại.',
            'description.required' => 'Vui lòng nhập mô tả phòng ban.',
        ]);

        // Tạo phòng ban mới
        $department = Department::create([
            'code' => $validated['code'],
            'description' => $validated['description'],
        ]);

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Tạo phòng ban thành công.',
            'department' => $department,
        ], 201);
    }


    public function edit(Department $department)
    {
        return response()->json($department);
    }

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate(
            [
                'code' => 'required|unique:departments,code,' . $id . '|max:255',
                'description' => 'required|max:1000',
            ],
            [
                'code.required' => 'Vui lòng nhập mã phòng ban.',
                'code.unique' => 'Mã phòng ban đã tồn tại.',
                'code.max' => 'Mã phòng ban không được vượt quá 255 ký tự.',
                'description.required' => 'Vui lòng nhập mô tả.',
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            ]
        );

        // Tìm và cập nhật phòng ban
        $department = Department::findOrFail($id);
        $department->update($request->only(['code', 'description']));

        // Trả về phản hồi thành công
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phòng ban thành công.',
            'data' => $department
        ], 200);
    }
}
