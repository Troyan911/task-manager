<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(rand(1, 3), true);
        $status = TaskStatus::ToDo;

        return [

            'user_id' => User::all()->random()?->id,
            'status' => $status,

            'title' => $title,
            'description' => fake()->sentence(rand(1, 5), true),
            'priority' => rand(1, 5),
        ];
    }

    public function withParent(): Factory
    {
        return $this->state(fn () => [
            'parent_id' => $parent_id = Task::all()->random()?->id,
            'user_id' => Task::find($parent_id)->user_id,
        ]);
    }

    public function withParentAndRandomStatus(): Factory
    {
        return $this->state(fn () => [
            'parent_id' => $parent_id = Task::all()->random()?->id,
            'status' => collect(TaskStatus::cases())->random(),
            'user_id' => Task::find($parent_id)->user_id,
        ]);
    }

    private function getRandomUser()
    {

    }
}
