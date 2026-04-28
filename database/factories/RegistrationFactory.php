<?php

namespace Database\Factories;

use App\Enums\RegistrationStatus;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory()->employee(),
            'workshop_id' => Workshop::factory(),
            'status'      => RegistrationStatus::Confirmed,
            'promoted_at' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => RegistrationStatus::Confirmed,
            'promoted_at' => null,
        ]);
    }

    public function waiting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => RegistrationStatus::Waiting,
            'promoted_at' => null,
        ]);
    }

    public function promoted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => RegistrationStatus::Confirmed,
            'promoted_at' => now(),
        ]);
    }
}
