<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Models\Task;
use App\Repositories\Contracts\TasksRepositoryContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class TasksRepository implements TasksRepositoryContract
{
    public function index(Request $request): Collection
    {
        $query = auth()->user()->tasks()->with('parent');

        if ($request->has('status')) {
            //todo
            $status = TaskStatus::tryFrom($request->input('status'));
            $query->byStatus($status);
        }

        if ($request->has('priority')) {
            $query->byPriority($request->input('priority'));
        }

        if ($request->has('title')) {
            $query->fieldContains('title', $request->input('title'));
        }

        if ($request->has('description')) {
            $query->fieldContains('description', $request->input('description'));
        }

        if ($request->has('sort1')) {
            $query->orderResponseBy($request->input('sort1'), $request->input('dir1'));
        }

        if ($request->has('sort2')) {
            $query->orderResponseBy($request->input('sort2'), $request?->input('dir2'));
        }

        $tasks = $query->get();

        return $tasks;
    }

    public function create(CreateTaskRequest $request): Task|false
    {
        try {
            $data = $request->validated();

            return Task::create($data);
        } catch (\Exception $exception) {
            logs()->warning($exception);

            return false;
        }
    }

    public function update(Task $task, EditTaskRequest $request): JsonResponse|bool
    {
        try {
            $data = $request->validated();
            $task->updateOrFail($data);

            return true;
        } catch (\Exception $exception) {
            logs()->warning($exception);

            return false;
        }
    }

    public function destroy(Task $task): JsonResponse|bool
    {
        try {
            if ($task->status === TaskStatus::Done) {
                return response()->json(['message' => "Can't delete task in status Done!"], 422);
            }

            if ($this->hasChildWithStatusDone($task->childs)) {
                return response()->json(['message' => "Can't delete task with child in status Done!"], 422);
            }
            auth()->user()->tasks()->find($task->id)->deleteOrFail();

            return response()->json(['code' => 200, 'message' => 'Task was deleted!'], 200);

        } catch (\Exception $exception) {
            logs()->warning($exception);

            return response()->json(['code' => 422, 'message' => 'Task wasn\'t deleted!'], 200);

        }
    }

    public function complete(Task $task): JsonResponse|bool
    {
        try {
            $task->status = TaskStatus::Done;
            $task->completed_at = Carbon::now();
            $task->save();
        } catch (Exception $exception) {
            logs()->warning($exception);
        }

        return true;
    }

    private function hasChildWithStatusDone(Collection $childs): bool
    {
        foreach ($childs as $child) {
            if ($child->status == TaskStatus::Done) {
                return true;
            }

            if ($child->childs) {
                $this->hasChildWithStatusDone($child->childs);
            }
        }

        return false;
    }
}
