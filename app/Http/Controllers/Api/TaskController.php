<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Http\Resources\Tasks\TaskCollection;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TasksRepositoryContract;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['parent'])
            ->whereNull('deleted_at')
            ->paginate(20);

        return (new TaskCollection($tasks))
            ->additional(
                [
                    'meta_data' => [
                        'total' => $tasks->total(),
                        'per_page' => $tasks->perPage(),
                        'page' => $tasks->currentPage(),
                        'to' => $tasks->lastPage(),
                        'path' => $tasks->path(),
                        'next' => $tasks->nextPageUrl(),
                    ],
                ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request, TasksRepositoryContract $repository)
    {
        return new TaskResource($repository->create($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTaskRequest $request, Task $task, TasksRepositoryContract $repository)
    {
        $repository->update($task, $request);
        return new TaskResource(Task::find($task->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, TasksRepositoryContract $repository)
    {
        return $repository->destroy($task);
    }
}
