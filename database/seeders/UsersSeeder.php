<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $adminEmail = env('ADMIN_EMAIL', 'admin@admin.com');

        if (! User::where('email', $adminEmail)->exists()) {
            (User::factory()->withEmail($adminEmail)->create());
        }

        User::factory(4)->create();
    }
}
