<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            // Randomly skip 20% of merchants
            if (mt_rand(1, 100) <= 30) {
                continue;
            }

            Note::factory()->count(3)->create([
                'merchant_id' => $merchant->id,
                'created_by' => $users->random()->id,
                'assigned_to' => rand(0, 1) ? $users->random()->id : null,
            ]);
        }
    }
}
