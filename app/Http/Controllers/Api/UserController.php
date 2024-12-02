<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Auth;

class UserController extends Controller
{
    function index()
    {
        try {
            // Sử dụng model User để lấy dữ liệu
            $users = User::with('creator')  // Eager load quan hệ creator
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->select('users.*', 'departments.description as name_department')
                ->whereNull('users.deleted_at') // Lọc những người dùng chưa bị xóa mềm
                ->get();

            // Gán thông tin người tạo (creator) vào mỗi người dùng
            foreach ($users as $user) {
                $user->created_by_name = $user->creator ? $user->creator->name : null;
            }

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCreate(){
        $departments = DB::table("departments")
            ->select("id as value", "description as label")
            ->get();

        return response()->json([
            "departments" => $departments
        ], 200);
    }

    // Đăng ký người dùng mới (nhân viên)
    public function register(Request $request)
    {

        // Validate dữ liệu đầu vào
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'department_id' => 'required|exists:departments,id',
            ],
            [
                'name.required' => 'Vui lòng nhập tên nhân viên.',
                'name.max' => 'Tên nhân viên không được vượt quá 255 ký tự.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
                'department_id.required' => 'Vui lòng chọn phòng ban.',
                'department_id.exists' => 'Phòng ban không hợp lệ.',
            ]
        );


        // Lấy thông tin phòng ban
        $department = Department::find($request->department_id);

        // Tính toán mã nhân viên (employee_code)
        $employeeCount = User::where('department_id', $request->department_id)->count();
        $employeeCode = $department->code . str_pad($employeeCount + 1, 4, '0', STR_PAD_LEFT);

        // Tạo tài khoản nhân viên mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
            'employee_code' => $employeeCode,
            'is_active' => 1, // Trạng thái mặc định là hoạt động
            'created_by' => auth()->id(), // Gán ID của người tạo
        ]);

        // Trả về phản hồi JSON
        return response()->json([
            'user' => $user,
            'message' => 'Tạo tài khoản nhân viên thành công',
        ], 201);
    }


    public function edit($id)
    {
        try {
            $departments = DB::table("departments")
                ->select("id as value", "code as label")
                ->get();
            // Tìm người dùng theo ID
            $user = User::with('creator') // Eager load quan hệ creator
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->select('users.*', 'departments.description as name_department')
                ->whereNull('users.deleted_at') // Lọc những người dùng chưa bị xóa mềm
                ->findOrFail($id);  // Nếu không tìm thấy sẽ ném lỗi 404

            // Gán thông tin người tạo vào người dùng (nếu có)
            $user->created_by_name = $user->creator ? $user->creator->name : null;

            return response()->json([
                'user' => $user,
                'departments' => $departments,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
            // Xác thực dữ liệu nếu cần (ví dụ, kiểm tra các trường nhập vào)
            $request->validate(
                [
                    'department_id' => 'required|exists:departments,id',
                    'name' => 'required|unique:users,name,' . $id . '|max:255',
                    'email' => 'required|email:rfc,dns|unique:users,email,' . $id . '|max:255',
                    'is_active' => 'required|boolean',
                ],
                [
                    'is_active.required' => 'Vui lòng nhập tình trạng.',
                    'is_active.boolean' => 'Tình trạng phải là kích hoạt hoặc khóa',
                    'department_id.required' => 'Vui lòng nhập phòng ban.',
                    'department_id.exists' => 'Phòng ban không hợp lệ.',
                    'name.required' => 'Vui lòng nhập tên tài khoản.',
                    'name.unique' => 'Tên tài khoản đã tồn tại.',
                    'email.required' => 'Vui lòng nhập email.',
                    'email.unique' => 'Email đã tồn tại.',
                    'email.email' => 'Email không đúng định dạng.'
                ]
            );
            
            // Lấy người dùng theo ID
            $user = User::findOrFail($id); // Nếu không tìm thấy sẽ ném lỗi 404
            $user->update($request->only(["is_active", "name", "email", "department_id"]));

            if ($request["change_password"] == "true") {
                $request->validate(
                    [
                        'password' => 'required|confirmed|min:8|max:255|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                    ],
                    [
                        'password.required' => 'Vui lòng nhập mật khẩu.',
                        'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
                        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                        'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa, một số và một ký tự đặc biệt.'
                    ]
                );
    
                $user->update([
                    "password" => bcrypt($request["password"]),
                ]);
            }

            return response()->json(['message' => 'Cập nhật người dùng thành công'], 200);

    }




    public function destroy(User $user)
    {
        // return response()->json($user);
        if ($user->delete()) {
            return response()->json(['message' => 'Xóa mềm người dùng thành công.'], 200);
        }

        return response()->json(['message' => 'Xóa người dùng thất bại.'], 500);
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->restore()) {
            return response()->json(['message' => 'Khôi phục người dùng thành công.'], 200);
        }

        return response()->json(['message' => 'Khôi phục người dùng thất bại.'], 500);
    }
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->forceDelete()) {
            return response()->json(['message' => 'Xóa hoàn toàn người dùng thành công.'], 200);
        }

        return response()->json(['message' => 'Xóa người dùng thất bại.'], 500);
    }
    public function trash()
    {
        try {
            $users = User::onlyTrashed()
                ->with('department') // Sử dụng quan hệ Eloquent để lấy thông tin phòng ban
                ->get();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
