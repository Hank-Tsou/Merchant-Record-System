<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\NoteType;
use App\Enums\NoteStatus;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'merchant_id' => Merchant::factory(),
            'created_by' => User::factory(),
            'assigned_to' => User::factory(), // or null with some chance
            'uid' => (string) Str::uuid(),
            'title' => $this->faker->sentence(3, true),
            'body' => $this->faker->paragraphs(1, true),
            'type' => $this->faker->randomElement(array_column(NoteType::cases(), 'value')),
            'status' => $this->faker->randomElement(array_column(NoteStatus::cases(), 'value')),
        ];
    }
}
