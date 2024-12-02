<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Các trường có thể được fillable (có thể gán giá trị qua mass-assignment)
    protected $fillable = ['code', 'description'];

    /**
     * Quan hệ với bảng 'users': Một phòng ban có thể có nhiều nhân viên
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    // Mutator: Tự động chuyển code thành chữ in hoa
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
}
