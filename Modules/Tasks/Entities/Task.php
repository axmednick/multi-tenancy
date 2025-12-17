<?php

namespace Modules\Tasks\Entities;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Users\Entities\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'assigned_to', 'status', 'due_date',
        'user_id',
        'content'
    ];


    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
