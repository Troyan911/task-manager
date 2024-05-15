<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Http\Resources\Tasks\TaskCollection;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Repositories\Contracts\TasksRepositoryContract;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //todo moove to repository
        $user = auth()->user();
        $query = $user->tasks()->with('parent', 'status');

        if ($request->has('status')) {
            $statusId = TaskStatus::where('name', $request->input('status'))->first()->id;
            $query->byStatus($statusId);
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

        return new TaskCollection($tasks);
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
        $user_id = auth()->user()->id;
        $tasks = Task::with('parent', 'status')->where('user_id', $user_id)->findOrFail($task->id);

        return new TaskResource($tasks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTaskRequest $request, Task $task, TasksRepositoryContract $repository)
    {
        $user_id = auth()->user()->id;
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

    public function done(EditTaskRequest $request, Task $task, TasksRepositoryContract $repository)
    {
        $repository->updateWithStatus($task, $request, \App\Models\TaskStatus::done()->first());

        return new TaskResource(Task::find($task->id));
    }
}
