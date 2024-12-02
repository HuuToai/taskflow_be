<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'text',
        'description',
        'duration',
        'progress',
        'start_date',
        'end_date',
        'parent',
        'open',
        'assignee',
        'status',
    ];

    /**
     * Các kiểu dữ liệu của các thuộc tính (casts)
     */
    protected $casts = [
        'progress' => 'float',
        'open' => 'boolean',
        'start_date' => 'datetime',
        'status' => 'string',
    ];
    /**
     * Định nghĩa mối quan hệ với model User (người được giao công việc)
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee');
    }

    /**
     * Định nghĩa mối quan hệ cha-con giữa các task
     */
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent');
    }

    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent');
    }
    public function links()
    {
        return $this->hasMany(Link::class, 'source');
    }
    /**
     * Mối quan hệ giữa Task và Link (target)
     */
    public function targetLinks()
    {
        return $this->hasMany(Link::class, 'target');
    }
}
