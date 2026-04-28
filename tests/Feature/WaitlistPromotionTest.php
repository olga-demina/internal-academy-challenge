<?php

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Models\Workshop;

test('first waitlisted user is promoted when a confirmed registration is
   cancelled', function () {
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    $confirmed =
        Registration::factory()->confirmed()->create(['workshop_id' =>
        $workshop->id]);
    $waiting = Registration::factory()->waiting()->create(['workshop_id'
    => $workshop->id]);

    $confirmed->delete();


    expect($waiting->fresh()->status)->toBe(RegistrationStatus::Confirmed);
});

test('waitlist promotion follows FIFO order', function () {
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    $confirmed =
        Registration::factory()->confirmed()->create(['workshop_id' =>
        $workshop->id]);
    $first = Registration::factory()->waiting()->create(['workshop_id'
    => $workshop->id]);
    $second = Registration::factory()->waiting()->create(['workshop_id'
    => $workshop->id]);

    $confirmed->delete();


    expect($first->fresh()->status)->toBe(RegistrationStatus::Confirmed);
    expect($second->fresh()->status)->toBe(RegistrationStatus::Waiting);
});

test('cancellation of a waiting registration does not trigger           
  promotion', function () {
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    Registration::factory()->confirmed()->create(['workshop_id' =>
    $workshop->id]);
    $waiting = Registration::factory()->waiting()->create(['workshop_id'
    => $workshop->id]);

    $waiting->delete();

    expect(Registration::count())->toBe(1);
});

test('cancellation with empty waitlist does nothing special', function () {
    $workshop = Workshop::factory()->create(['capacity' => 1]);
    $confirmed =
        Registration::factory()->confirmed()->create(['workshop_id' =>
        $workshop->id]);

    $confirmed->delete();

    expect(Registration::count())->toBe(0);
});
