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
            $noteCount = mt_rand(0, 5);
            Note::factory()->count($noteCount)->create([
                'merchant_id' => $merchant->id,
                'created_by' => $users->random()->id,
                'assigned_to' => rand(0, 1) ? $users->random()->id : null,
            ]);
        }
    }
}
