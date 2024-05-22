<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface TasksRepositoryContract
{
    public function index(Request $request): Collection;

    public function create(CreateTaskRequest $request): Task|false;

    public function update(Task $task, EditTaskRequest $request): JsonResponse|bool;

    public function destroy(Task $task): JsonResponse|bool;

    public function complete(Task $task): JsonResponse|bool;
}
