<?php

namespace App\Repositories;

use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Repositories\Contracts\TasksRepositoryContract;

class TasksRepository implements TasksRepositoryContract
{
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

    public function update(Task $task, EditTaskRequest $request): bool
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

    public function updateWithStatus(Task $task, EditTaskRequest $request, TaskStatus $status)
    {
        $task->setStatus($status);

        return $this->update($task, $request);
    }

    /**
     * @return bool|void
     */
    public function destroy(Task $task): bool
    {
        $task_id = $task->id;

        try {
            if (auth()->user()->tasks()->find($task_id)->exists()
                && ! $task->childs()->where('parent_id', $task_id)->exists()) {
                auth()->user()->tasks()->find($task_id)->deleteOrFail();

                return true;
            }
        } catch (\Exception $exception) {
            logs()->warning($exception);

            return false;
        }
    }
}
