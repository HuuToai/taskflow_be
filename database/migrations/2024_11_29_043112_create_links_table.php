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
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id')->comment('Khóa chính tự tăng, định danh duy nhất cho mỗi liên kết');
            $table->string('type')->comment('Loại liên kết (ví dụ: finish-to-start, start-to-start, v.v.)');
            $table->unsignedBigInteger('source')->nullable()->comment('ID của công việc nguồn (công việc bắt đầu trước)');
            $table->unsignedBigInteger('target')->nullable()->comment('ID của công việc đích (công việc phải chờ công việc nguồn hoàn thành)');
            $table->timestamps();
            $table->foreign('source')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('target')->references('id')->on('tasks')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
