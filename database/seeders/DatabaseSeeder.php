<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
    'name' => 'Admin Aisyah',
    'email' => 'admin@gmail.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
]);

\App\Models\User::create([
    'name' => 'User Biasa',
    'email' => 'user@gmail.com',
    'password' => bcrypt('password123'),
    'role' => 'user',
]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
