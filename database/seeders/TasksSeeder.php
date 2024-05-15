<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory(10)->create();
        Task::factory(10)->withParent()->create();
        Task::factory(10)->withParent()->create();
        Task::factory(10)->withParent()->create();
        Task::factory(50)->withParentAndRandomStatus()->create();
    }
}
