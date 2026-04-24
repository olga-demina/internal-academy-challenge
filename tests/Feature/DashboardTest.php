<?php

use App\Models\User;

test('guest is redirected to login when accessing admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing employee dashboard', function () {
    $this->get(route('employee.dashboard'))->assertRedirect(route('login'));
});

test('admin can access admin dashboard', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('employee can access employee dashboard', function () {
    $user = User::factory()->employee()->create();

    $this->actingAs($user)
        ->get(route('employee.dashboard'))
        ->assertOk();
});
