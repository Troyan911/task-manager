<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus as TaskStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    protected $casts = [
        'name' => TaskStatusEnum::class,
    ];

    public function scopeToDo(Builder $query): Builder
    {
        return $this->statusQuery($query, TaskStatusEnum::ToDo);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $this->statusQuery($query, TaskStatusEnum::Done);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected function statusQuery(Builder $query, TaskStatusEnum $status): Builder
    {
        return $query->where('name', $status->value);
    }
}
