<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Tasks;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'numeric',  'in:'.auth()->user()->id],
            'status' => ['required', 'string', new Enum(TaskStatus::class)],
            'parent_id' => ['nullable', 'numeric', 'exists:'.Task::class.',id'],

            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:'.Task::class],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'integer', 'between:1,5'],
            'completed_at' => 'nullable|date'
        ];
    }
}
