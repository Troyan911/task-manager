<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\TaskStatus as TaskStatusModel;
use Illuminate\Database\Seeder;

class TaskStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (TaskStatus::cases() as $case) {
            TaskStatusModel::create(['name' => $case->value]);

        }
    }
}
