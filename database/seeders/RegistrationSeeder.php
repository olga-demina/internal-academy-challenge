<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $employee     = User::where('email', 'employee@academy.test')->first();
        $employees    = User::where('role', 'employee')->get();
        $fullWorkshop = Workshop::where('title', 'Full Workshop – Waiting List Demo')->first();
        $tomorrow     = Workshop::where('title', 'Tomorrow\'s Workshop – Reminder Demo')->first();
        $future       = Workshop::whereDate('starts_at', '>', now())
            ->where('title', 'not like', '%Demo%')
            ->where('title', 'not like', '%Today%')
            ->first();
        $past         = Workshop::whereDate('starts_at', '<', now())->get();

        Registration::factory()->confirmed()->create([
            'user_id'     => $employee->id,
            'workshop_id' => $tomorrow->id,
        ]);

        if ($future) {
            Registration::factory()->confirmed()->create([
                'user_id'     => $employee->id,
                'workshop_id' => $future->id,
            ]);
        }

        $confirmedEmployee = $employees->where('email', '!=', 'employee@academy.test')->first();
        Registration::factory()->confirmed()->create([
            'user_id'     => $confirmedEmployee->id,
            'workshop_id' => $fullWorkshop->id,
        ]);

        $waitingEmployees = $employees->where('email', '!=', 'employee@academy.test')
            ->where('id', '!=', $confirmedEmployee->id)
            ->take(3);

        foreach ($waitingEmployees as $waitingEmployee) {
            Registration::factory()->waiting()->create([
                'user_id'     => $waitingEmployee->id,
                'workshop_id' => $fullWorkshop->id,
            ]);
        }

        foreach ($past as $pastWorkshop) {
            $randomEmployees = $employees->random(min(5, $employees->count()));
            foreach ($randomEmployees as $randomEmployee) {
                Registration::factory()->confirmed()->create([
                    'user_id'     => $randomEmployee->id,
                    'workshop_id' => $pastWorkshop->id,
                ]);
            }
        }

        if ($past->count() > 0) {
            $randomEmployees = $employees->random(min(3, $employees->count()));
            foreach ($randomEmployees as $randomEmployee) {
                Registration::factory()->promoted()->create([
                    'user_id'     => $randomEmployee->id,
                    'workshop_id' => $past->random()->id,
                ]);
            }
        }
    }
}
