<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['type', 'source', 'target'];
    /**
     * Quan hệ với task nguồn (source)
     */
    public function sourceTask()
    {
        return $this->belongsTo(Task::class, 'source');
    }

    /**
     * Quan hệ với task đích (target)
     */
    public function targetTask()
    {
        return $this->belongsTo(Task::class, 'target');
    }
}
