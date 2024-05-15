<?php

namespace App\Models;

use App\Enums\TaskStatus as TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    //    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status_id',
        'parent_id',

        'title',
        'description',
        'priority',
    ];

    //    protected $dates = ['deleted_at'];

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function setStatus(TaskStatusEnum $status)
    {
        $this->status->name = $status->name;
    }

    public function setStatusDone()
    {
        $this->setStatus(TaskStatusEnum::Done);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_id', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeFieldContains($query, $field, $value)
    {
        return $query->where($field, 'like', "%$value%");
    }

    public function scopeOrderResponseBy($query, $field, $direction)
    {
        return $query->orderBy($field, $direction ?? 'desc');
    }
}
