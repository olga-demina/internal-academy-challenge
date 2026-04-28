<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@academy.test')->first();

        Workshop::factory()->count(3)->create(['user_id' => $admin->id]);

        Workshop::factory()->count(3)->withHighCapacity()->create(['user_id' => $admin->id]);

        Workshop::factory()->count(3)->withLowCapacity()->create([
            'user_id' => $admin->id,
            'title'   => 'Full Workshop – Waiting List Demo',
        ]);

        Workshop::factory()->tomorrow()->create([
            'user_id'  => $admin->id,
            'title'    => 'Tomorrow\'s Workshop – Reminder Demo',
            'capacity' => 20,
        ]);

        $slotA = now()->addDays(7)->setTime(9, 0);
        $slotB = now()->addDays(7)->setTime(10, 0);

        Workshop::factory()->startingAt($slotA)->create([
            'user_id'  => $admin->id,
            'title'    => 'Morning Session A (09:00–11:00)',
            'capacity' => 20,
        ]);

        Workshop::factory()->startingAt($slotB)->create([
            'user_id'  => $admin->id,
            'title'    => 'Morning Session B (10:00–12:00) — overlaps with A',
            'capacity' => 20,
        ]);

        Workshop::factory()->startingAt(now()->addDays(14)->setTime(14, 0))->create([
            'user_id'     => $admin->id,
            'title'       => 'Advanced Leadership Skills',
            'description' => 'A highly sought-after workshop on leadership and team management.',
            'capacity'    => 5,
        ]);

        Workshop::factory()->past()->count(3)->create(['user_id' => $admin->id]);

        Workshop::factory()->today()->create([
            'user_id'  => $admin->id,
            'title'    => 'Today\'s Workshop',
            'capacity' => 15,
        ]);
    }
}
