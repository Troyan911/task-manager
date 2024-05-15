<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@admin.com');
        $adminEmail2 = env('ADMIN_EMAIL', 'admin2@admin.com');

        if (! User::where('email', $adminEmail)->exists()) {
            (User::factory()->withEmail($adminEmail)->create());
        }

        if (! User::where('email', $adminEmail2)->exists()) {
            (User::factory()->withEmail($adminEmail2)->create());
        }

        User::factory(3)->create();
    }
}
