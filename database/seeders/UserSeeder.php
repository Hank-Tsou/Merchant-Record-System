<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(5)->create();

        // Optionally create a known user for login testing
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@google.com',
            'password' => Hash::make('password'),
        ]);
    }
}
