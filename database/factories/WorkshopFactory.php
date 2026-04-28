<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('+1 day', '+30 days');
        $endsAt = (clone $startsAt)->modify('+' . fake()->numberBetween(1, 3) . ' hours');

        return [
            'user_id'     => User::factory()->admin(),
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
            'starts_at'   => $startsAt,
            'ends_at'     => $endsAt,
            'capacity'    => fake()->numberBetween(1, 100),
        ];
    }

    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $startsAt = fake()->dateTimeBetween('-1 year', '-1 day');
            $endsAt = (clone $startsAt)->modify('+' . fake()->numberBetween(1, 3) . ' hours');
            return [
                'starts_at' => $startsAt,
                'ends_at'   => $endsAt,
            ];
        });
    }

    public function today(): static
    {
        return $this->state(function (array $attributes) {
            $startsAt = now()->setTime(10, 0);
            $endsAt = (clone $startsAt)->modify('+' . fake()->numberBetween(1, 3) . ' hours');
            return [
                'starts_at' => $startsAt,
                'ends_at'   => $endsAt,
            ];
        });
    }

    public function tomorrow(): static
    {
        return $this->state(function (array $attributes) {
            $startsAt = now()->addDay()->setTime(10, 0);
            $endsAt = (clone $startsAt)->modify('+' . fake()->numberBetween(1, 3) . ' hours');
            return [
                'starts_at' => $startsAt,
                'ends_at'   => $endsAt,
            ];
        });
    }

    public function startingAt(\DateTimeInterface $startsAt, int $durationHours = 2): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => $startsAt,
            'ends_at'   => (clone $startsAt)->modify("+{$durationHours} hours"),
        ]);
    }

    public function withHighCapacity(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => 100,
        ]);
    }

    public function withLowCapacity(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => 1,
        ]);
    }
}
