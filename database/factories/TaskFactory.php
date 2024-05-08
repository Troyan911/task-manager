<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskStatus;
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

        return [

            'user_id' => User::all()->random()?->id,
            'status_id' => TaskStatus::all()->random()?->id,

            'title' => $title,
            'description' => fake()->sentence(rand(1, 5), true),
            'priority' => rand(1, 5),
        ];
    }

    public function withParent(): Factory
    {
        return $this->state(fn () => ['parent_id' => Task::all()->random()?->id]);
    }
}
