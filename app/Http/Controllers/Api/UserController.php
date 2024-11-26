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
use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{
    function index()
    {
        try {
            $users = DB::table('users')
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->select('users.*', 'departments.description as name_department')
                ->whereNull('users.deleted_at') // Lọc những người dùng chưa bị xóa mềm
                ->get();
    
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
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
    public function trash(){
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
