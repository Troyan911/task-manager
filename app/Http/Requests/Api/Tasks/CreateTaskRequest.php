<?php

namespace App\Http\Requests\Api\Tasks;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
        return [
            'user_id' => ['required', 'numeric',  'exists:'.User::class.',id'],
            'status_id' => ['required', 'numeric',  'exists:'.TaskStatus::class.',id'],
            'parent_id' => ['nullable', 'numeric', 'exists:'.Task::class.',id'],

            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:'.Task::class],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'integer', 'between:1,5'],
        ];
    }
}
