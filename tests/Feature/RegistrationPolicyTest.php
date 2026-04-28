<?php

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

// --- create (sign up) ---

test('employee can register for a workshop with available seats', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create(['capacity' => 5]);

    expect($employee->can('create', [Registration::class, $workshop]))->toBeTrue();
});

test('employee can join waitlist for a full workshop', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    Registration::factory()->confirmed()->create(['workshop_id' => $workshop->id]);

    expect($employee->can('create', [
        Registration::class,
        $workshop
    ]))->toBeTrue();
});

test('employee cannot register for the same workshop twice', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create(['capacity' => 10]);
    Registration::factory()->confirmed()->create([
        'user_id' => $employee->id,
        'workshop_id' => $workshop->id,
    ]);

    expect($employee->can('create', [Registration::class, $workshop]))->toBeFalse();
});

test('employee cannot join waitlist if already waiting', function () {
    $employee = User::factory()->employee()->create();
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    Registration::factory()->waiting()->create([
        'user_id' => $employee->id,
        'workshop_id' => $workshop->id,
    ]);

    expect($employee->can('create', [
        Registration::class,
        $workshop
    ]))->toBeFalse();
});


// --- overlap ---

test('employee cannot register for a workshop that overlaps with a confirmed registration', function () {
    $employee = User::factory()->employee()->create();
    $slot = now()->addDay()->setTime(10, 0);

    $existing = Workshop::factory()->startingAt($slot)->create(['capacity' => 10]); // 10:00–12:00
    Registration::factory()->confirmed()->create([
        'user_id'     => $employee->id,
        'workshop_id' => $existing->id,
    ]);

    $overlapping = Workshop::factory()->startingAt($slot->setTime(11, 0))->create(['capacity' => 10]); // 11:00–13:00

    expect($employee->can('create', [Registration::class, $overlapping]))->toBeFalse();
});


test('admin cannot register for a workshop', function () {
    $admin = User::factory()->admin()->create();
    $workshop = Workshop::factory()->create(['capacity' => 10]);

    expect($admin->can('create', [Registration::class, $workshop]))->toBeFalse();
});

// --- delete (cancel) ---

test('employee can cancel their own confirmed registration', function () {
    $employee = User::factory()->employee()->create();
    $registration = Registration::factory()->confirmed()->create(['user_id' => $employee->id]);

    expect($employee->can('delete', $registration))->toBeTrue();
});

test('employee cannot cancel another user registration', function () {
    $employee = User::factory()->employee()->create();
    $other = User::factory()->employee()->create();
    $registration = Registration::factory()->confirmed()->create(['user_id' => $other->id]);

    expect($employee->can('delete', $registration))->toBeFalse();
});

test('employee can cancel their own waiting registration', function () {
    $employee = User::factory()->employee()->create();
    $registration =
        Registration::factory()->waiting()->create(['user_id' =>
        $employee->id]);

    expect($employee->can('delete', $registration))->toBeTrue();
});
