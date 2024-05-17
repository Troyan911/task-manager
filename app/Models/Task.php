<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     description="Task model",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID of the task",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the task",
 *         example="Example Task"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the task",
 *         example="This is an example task description"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date and time",
 *         example="2022-05-05T10:15:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date and time",
 *         example="2022-05-05T10:15:00Z"
 *     ),
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'parent_id',

        'title',
        'description',
        'priority',
    ];

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

    /**
     * @return mixed
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_id', $status);
    }

    /**
     * @return mixed
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * @return mixed
     */
    public function scopeFieldContains($query, $field, $value)
    {
        return $query->where($field, 'like', "%$value%");
    }

    /**
     * @return mixed
     */
    public function scopeOrderResponseBy($query, $field, $direction)
    {
        return $query->orderBy($field, $direction ?? 'asc');
    }
}
