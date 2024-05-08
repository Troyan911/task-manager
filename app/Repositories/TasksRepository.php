<?php

namespace App\Repositories;

use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Models\Task;
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

    public function destroy(Task $task): bool
    {
        try {
            //todo can't delete
//            if ($task->childs()->exists()) {
//                $task->childs()->update(['parent_id' => null]);
//            }
//
            return $task->deleteOrFail();
        } catch (\Exception $exception) {
            logs()->warning($exception);

            return false;
        }
    }
}
