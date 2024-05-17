<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Tasks;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $task = $this->route('task');

        return [
            'user_id' => ['required', 'numeric', 'in:'.auth()->user()->id],
            'status_id' => ['required', 'numeric', 'exists:'.TaskStatus::class.',id'],
            'parent_id' => ['nullable', 'numeric', 'exists:'.Task::class.',id'],

            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Task::class, 'title')->ignore($task->id)],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'integer', 'between:1,5'],
        ];
    }
}
