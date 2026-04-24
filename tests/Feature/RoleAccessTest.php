<?php

use App\Models\User;

test('employee cannot access admin dashboard and is redirected to their own', function () {
    $user = User::factory()->employee()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertRedirect(route('employee.dashboard'));
});

test('admin cannot access employee dashboard and is redirected to their own', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('employee.dashboard'))
        ->assertRedirect(route('admin.dashboard'));
});
