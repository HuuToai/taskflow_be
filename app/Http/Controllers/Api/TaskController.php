<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Link;
use App\Models\User;

class TaskController extends Controller
{
    // Lấy danh sách tasks
    public function index()
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }
    public function getGanttData()
    {
        // Truy vấn danh sách tasks
        $tasks = Task::all()->map(function ($task) {
            return [
                'id' => $task->id,
                'text' => $task->text,
                'description' => $task->description,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'duration' => $task->duration,
                'parent' => $task->parent,
                'progress' => $task->progress,
                'open' => $task->open  == 1 ? true : false,
                'status' => $task->status,
                'assignee' => $task->assignee,
            ];
        });
        // Truy vấn danh sách links
        $links = Link::all()->map(function ($link) {
            return [
                'id' => $link->id,
                'source' => $link->source,
                'target' => $link->target,
                'type' => $link->type,
            ];
        });
        $users = User::all()->map(function ($user) {
            return [
                'key' => $user->id,
                'label' => $user->name,
            ];
        });

        // Trả về dữ liệu JSON trong cấu trúc mong muốn
        return response()->json([
            'tasks' => [
                'data' => $tasks,
                'links' => $links,
                'users' => $users,
            ],
        ]);
    }



    // Thêm mới task
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu có hợp lệ không
        $validatedData = $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'progress' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'parent' => 'nullable|integer',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'user' => 'nullable|integer|exists:users,id', // Kiểm tra user có tồn tại không
        ]);

        // Tạo task mới
        $task = Task::create([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'duration' => $validatedData['duration'],
            'progress' => $validatedData['progress'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'parent' => $validatedData['parent'] ?? null,
            'status' => $validatedData['status'],
            'assignee' => $validatedData['user'] ?? null, // Liên kết với user nếu có
        ]);

        return response()->json(['task' => $task], 201);
    }

    // Cập nhật task
    public function update(Request $request, $id)
    {
        // Tìm task theo ID
        $task = Task::findOrFail($id);

        // Kiểm tra và cập nhật các trường có dữ liệu trong request
        $validatedData = $request->validate([
            'text' => 'nullable|string|max:255', // Cho phép rỗng nhưng nếu có thì phải là chuỗi
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'progress' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'parent' => 'nullable|integer',
            'status' => 'nullable|in:pending,in_progress,completed,on_hold,canceled',
            'user' => 'nullable|exists:users,id', // Kiểm tra user có tồn tại trong bảng users không
        ]);

        // Kiểm tra nếu có trường `user` và gán vào `assignee`
        if ($request->has('user')) {
            $validatedData['assignee'] = $request->input('user');
            unset($validatedData['user']);  // Xóa trường `user` sau khi đã chuyển giá trị sang `assignee`
        }

        // Cập nhật các trường có dữ liệu trong validatedData
        $task->update(array_filter($validatedData, function ($value) {
            // Chỉ cập nhật những trường có giá trị (không phải null hoặc rỗng)
            return !is_null($value) && $value !== '';
        }));

        return response()->json($task);
    }



    // Xóa task
    public function destroy($id)
    {
        // Tìm task theo ID, nếu không tìm thấy sẽ trả về lỗi 404
        $task = Task::findOrFail($id);

        // Xóa task
        $task->delete();

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Task đã được xóa thành công.']);
    }

}
