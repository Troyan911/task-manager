<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->delete();
        Task::factory(10)->create();
        Task::factory(10)->withParent()->create();
        Task::factory(10)->withParent()->create();
        Task::factory(50)->withParent()->create();
    }
}
