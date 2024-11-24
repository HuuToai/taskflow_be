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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID người dùng'); // ID duy nhất
            $table->string('employee_code')->unique()->nullable()->comment('Mã nhân viên'); // Mã nhân viên duy nhất
            $table->string('name')->comment('Tên người dùng');
            $table->string('email')->unique()->comment('Địa chỉ email của người dùng');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời điểm email được xác minh');
            $table->string('password')->comment('Mật khẩu người dùng');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt của tài khoản');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Người tạo tài khoản');
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('set null')
                ->comment('Phòng ban của người dùng');
            $table->rememberToken()->comment('Token dùng để ghi nhớ trạng thái đăng nhập');
            $table->timestamps();
        });

        // Tạo bảng password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('Email của người cần đặt lại mật khẩu');
            $table->string('token')->comment('Token để xác nhận yêu cầu đặt lại mật khẩu');
            $table->timestamp('created_at')->nullable()->comment('Thời điểm yêu cầu đặt lại mật khẩu được tạo');
        });

        // Tạo bảng sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('ID phiên làm việc');
            $table->foreignId('user_id')->nullable()->index()->comment('ID người dùng liên kết với phiên làm việc');
            $table->string('ip_address', 45)->nullable()->comment('Địa chỉ IP của người dùng');
            $table->text('user_agent')->nullable()->comment('Thông tin user agent của trình duyệt');
            $table->longText('payload')->comment('Dữ liệu của phiên làm việc');
            $table->integer('last_activity')->index()->comment('Thời gian hoạt động gần nhất của phiên làm việc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
