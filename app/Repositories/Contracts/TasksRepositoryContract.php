<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Models\Task;

interface TasksRepositoryContract
{
    public function create(CreateTaskRequest $request): Task|false;

    public function update(Task $task, EditTaskRequest $request): bool;

    public function destroy(Task $task): bool;
}
