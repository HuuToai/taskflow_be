<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    // Lấy danh sách links
    public function index()
    {
        $links = Link::all();
        return response()->json($links);
    }

    // Thêm mới link
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validatedData = $request->validate([
            'source' => 'required|integer|exists:tasks,id', // ID của task nguồn
            'target' => 'required|integer|exists:tasks,id', // ID của task đích
            'type' => 'required|in:0,1,2,3', // Kiểu liên kết (Finish-to-Start, Start-to-Start, etc.)
        ]);

        // Tạo liên kết mới
        $link = Link::create([
            'source' => $validatedData['source'],
            'target' => $validatedData['target'],
            'type' => $validatedData['type'],
        ]);

        return response()->json(['link' => $link], 201);
    }

    // Xóa link
    public function destroy($id)
    {
        Link::destroy($id);
        return response()->json(null, 204);
    }
}
