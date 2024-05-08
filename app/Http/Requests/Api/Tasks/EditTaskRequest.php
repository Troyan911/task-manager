<?php

namespace App\Http\Requests\Api\Tasks;

use App\Models\Product;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $taskId = $this->route('task')->id;

        return [
            'user_id' => ['required', 'numeric',  'exists:' . User::class . ',id'],
            'status_id' => ['required', 'numeric',  'exists:' . TaskStatus::class . ',id'],
            'parent_id' => ['nullable', 'numeric', 'exists:'.Task::class.',id'],

            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Task::class, 'title')->ignore($taskId)],
            'description' => ['nullable', 'string'],
            'priority' => ['required','integer', 'between:1,5'],
        ];
    }
}
