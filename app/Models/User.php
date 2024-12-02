<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Các trường có thể được fillable (có thể gán giá trị qua mass-assignment)
    protected $fillable = ['name', 'email', 'password', 'is_active', 'created_by', 'department_id', 'employee_code', 'deleted_at'];


    /**
     * Quan hệ với bảng 'departments': Mỗi người dùng thuộc một phòng ban
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Quan hệ với bảng 'users' để biết người tạo người dùng này
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assignee');
    }

    /**
     * Tạo mã nhân viên tự động khi tạo người dùng
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->department_id) {
                // Lấy mã phòng ban
                $department = Department::find($user->department_id);

                if ($department && $department->code) {
                    // Đếm số nhân viên hiện tại trong phòng ban
                    $count = User::where('department_id', $user->department_id)->count() + 1;

                    // Tạo mã nhân viên
                    $user->employee_code = $department->code . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
