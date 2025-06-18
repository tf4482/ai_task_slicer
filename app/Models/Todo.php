<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'parent_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Todo::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Todo::class, 'parent_id');
    }

    public function isSubtask(): bool
    {
        return !is_null($this->parent_id);
    }

    public function isMainTask(): bool
    {
        return is_null($this->parent_id);
    }
}
