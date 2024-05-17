<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\Tasks\EditTaskRequest;
use App\Http\Resources\Tasks\TaskCollection;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TasksRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Constructor
     * AuthorizeResource: Task
     */
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/tasks",
     *     operationId="getTasksList",
     *     tags={"Tasks"},
     *     summary="Get list of tasks",
     *     description="Returns a list of tasks",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request, TasksRepositoryContract $repository): TaskCollection
    {
        return new TaskCollection($repository->show($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     * path="/tasks",
     * operationId="createTask",
     * tags={"Tasks"},
     * summary="Create task entity",
     * description="Create task entity",
     * security={{"bearerAuth": {}}},
     *
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     *
     * @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     *
     * @OA\Response(
     * response=404,
     * description="Task not found"
     * )
     * )
     */
    public function store(CreateTaskRequest $request, TasksRepositoryContract $repository): TaskResource
    {
        return new TaskResource($repository->create($request));
    }

    /**
     * @OA\Get(
     *        path="/tasks/{id}",
     *        operationId="getTask",
     *        tags={"Tasks"},
     *        summary="Get task entity",
     *        description="Get task entity",
     *        security={{"bearerAuth": {}}},
     *
     *        @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="ID of the task",
     *        required=true,
     *
     *        @OA\Schema(
     *            type="integer"
     *        ),
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(ref="#/components/schemas/Task")
     *       ),
     *
     *       @OA\Response(
     *           response=404,
     *           description="Task not found"
     *       )
     *   )
     */
    public function show(Task $task): JsonResponse|TaskResource
    {
        if (! auth()->user()->tasks()->find($task->id)) {
            return response()->json(['code' => 404, 'message' => 'Task not found!'], 404);
        }

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Patch(
     *       path="/tasks/{id}",
     *       operationId="updateTask",
     *       tags={"Tasks"},
     *       summary="Update task",
     *       description="Update task",
     *       security={{"bearerAuth": {}}},
     *
     *       @OA\Parameter(
     *       name="id",
     *       in="path",
     *       description="ID of the task",
     *       required=true,
     *
     *       @OA\Schema(
     *           type="integer"
     *       ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(ref="#/components/schemas/Task")
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Task not found"
     *      )
     *  )
     */
    public function update(EditTaskRequest $request, Task $task, TasksRepositoryContract $repository): JsonResponse|TaskResource
    {
        if (! auth()->user()->tasks()->find($task->id)) {
            return response()->json(['code' => 404, 'message' => 'Task not found!'], 404);
        }
        $repository->update($task, $request);

        return new TaskResource(Task::find($task->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *         path="/tasks/{id}",
     *         operationId="deleteTask",
     *         tags={"Tasks"},
     *         summary="Delete task entity",
     *         description="Delete task entity",
     *         security={{"bearerAuth": {}}},
     *
     *         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *        ),
     *
     *        @OA\Response(
     *            response=200,
     *            description="Successful operation",
     *
     *            @OA\JsonContent(ref="#/components/schemas/Task")
     *        ),
     *
     *        @OA\Response(
     *            response=404,
     *            description="Task not found"
     *        )
     *    )
     */
    public function destroy(Task $task, TasksRepositoryContract $repository): JsonResponse|TaskResource
    {
        if (! auth()->user()->tasks()->find($task->id)) {
            return response()->json(['code' => 404, 'message' => 'Task not found!'], 404);
        }

        return $repository->destroy($task);
    }

    /**
     * @OA\Patch(
     *      path="/tasks/{id}/done",
     *      operationId="setTaskStatusDone",
     *      tags={"Tasks"},
     *      summary="Set task status to done",
     *      description="Set task status to done",
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of the task",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="integer"
     *      ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function done(Task $task, TasksRepositoryContract $repository): JsonResponse|TaskResource
    {
        if (! auth()->user()->tasks()->find($task->id)) {
            return response()->json(['code' => 404, 'message' => 'Task not found!'], 404);
        }

        $repository->setStatusDone($task);

        return new TaskResource(Task::find($task->id));
    }
}
