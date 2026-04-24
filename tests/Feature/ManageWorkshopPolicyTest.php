<?php

use App\Models\User;
use App\Models\Workshop;

test('admin can view and create workshops', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->can('viewAny', Workshop::class))->toBeTrue();
    expect($admin->can('create', Workshop::class))->toBeTrue();
});

test('admin can update and delete their own workshop', function () {
    $admin = User::factory()->admin()->create();
    $workshop = Workshop::factory()->create(['user_id' => $admin->id]);

    expect($admin->can('update', $workshop))->toBeTrue();
    expect($admin->can('delete', $workshop))->toBeTrue();
});

test('admin cannot update or delete another admin workshop', function () {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->admin()->create();
    $workshop = Workshop::factory()->create(['user_id' => $other->id]);

    expect($admin->can('update', $workshop))->toBeFalse();
    expect($admin->can('delete', $workshop))->toBeFalse();
});

test('employee can view workshops', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create();

    expect($employee->can('viewAny', Workshop::class))->toBeTrue();
    expect($employee->can('view', $workshop))->toBeTrue();
});

test('employee cannot create, update, or delete workshops', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create();

    expect($employee->can('create', Workshop::class))->toBeFalse();
    expect($employee->can('update', $workshop))->toBeFalse();
    expect($employee->can('delete', $workshop))->toBeFalse();
});
