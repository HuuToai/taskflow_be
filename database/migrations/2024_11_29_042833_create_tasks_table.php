<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->comment('Khóa chính tự tăng, định danh duy nhất cho mỗi công việc');
            $table->string('text')->comment('Tên hoặc mô tả của công việc');
            $table->string('description')->nullable()->comment('Mô tả hoặc ghi chú công việc');
            $table->integer('duration')->comment('Thời gian (số ngày) mà công việc sẽ kéo dài');
            $table->float('progress')->comment('Tiến độ công việc (từ 0 đến 100)');
            $table->dateTime('start_date')->comment('Thời gian bắt đầu công việc');
            $table->dateTime('end_date')->comment('Thời gian kết thúc công việc');
            $table->integer('parent')->comment('ID của công việc cha (nếu có), dùng để xác định quan hệ cha-con giữa các công việc');
            $table->boolean('open')->default(true)->comment('Trạng thái mở hoặc đóng của task (true: mở, false: đóng)');
            $table->unsignedBigInteger('assignee')->nullable()->comment('ID của người phụ trách công việc');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold', 'canceled'])->default('pending')->comment('Trạng thái của công việc');
            $table->timestamps();

            // Tạo khóa ngoại cho assignee (liên kết đến bảng users)
            $table->foreign('assignee')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
