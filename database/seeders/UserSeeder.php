<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::factory()->admin()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@academy.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->admin()->create([
            'name'     => 'Admin User 2',
            'email'    => 'admin2@academy.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->employee()->create([
            'name'     => 'Employee User',
            'email'    => 'employee@academy.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->employee()->create([
            'name'     => 'Employee User 2',
            'email'    => 'employee2@academy.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->employee()->count(10)->create();
    }
}
