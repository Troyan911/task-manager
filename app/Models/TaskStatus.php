<?php

namespace App\Models;

use App\Enums\TaskStatus as Status;
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
        'name' => Status::class,
    ];

    public function scopeToDo(Builder $query): Builder
    {
        return $this->statusQuery($query, Status::ToDo);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $this->statusQuery($query, Status::InProgress);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $this->statusQuery($query, Status::Done);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected function statusQuery(Builder $query, \App\Enums\TaskStatus $status): Builder
    {
        return $query->where('name', $status->value);
    }
}
