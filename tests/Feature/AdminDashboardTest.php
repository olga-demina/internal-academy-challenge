<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('most popular workshop is the one with most total            
  registrations', function () {
    $admin = User::factory()->admin()->create();

    $popular = Workshop::factory()->create(['user_id' =>
    $admin->id, 'capacity' => 10]);
    $other   = Workshop::factory()->create(['user_id' =>
    $admin->id, 'capacity' => 10]);

    Registration::factory()->confirmed()->count(5)->create(['workshop_id' => $popular->id]);
    Registration::factory()->confirmed()->count(2)->create(['workshop_id' => $other->id]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertInertia(
            fn($page) => $page
                ->component('Admin/Dashboard')
                ->where('mostPopular.title', $popular->title)
        );
});

test('waiting registrations count toward popularity', function () {
    $admin    = User::factory()->admin()->create();
    $workshop = Workshop::factory()->create(['user_id' => $admin->id, 'capacity' => 3]);

    Registration::factory()->confirmed()->count(3)->create(['workshop_id' => $workshop->id]);
    Registration::factory()->waiting()->count(4)->create(['workshop_id' => $workshop->id]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertInertia(
            fn($page) => $page
                ->where('mostPopular.total',     7)
                ->where('mostPopular.confirmed', 3)
                ->where('mostPopular.waiting',   4)
        );
});


test('admin only sees their own workshops in statistics', function () {
    $admin1 = User::factory()->admin()->create();
    $admin2 = User::factory()->admin()->create();

    $own   = Workshop::factory()->create(['user_id' => $admin1->id, 'capacity' => 10]);
    $other = Workshop::factory()->create(['user_id' => $admin2->id, 'capacity' => 10]);

    Registration::factory()->confirmed()->count(2)->create(['workshop_id' => $own->id]);
    Registration::factory()->confirmed()->count(10)->create(['workshop_id' => $other->id]);

    $this->actingAs($admin1)
        ->get(route('admin.dashboard'))
        ->assertInertia(
            fn($page) => $page
                ->where('mostPopular.title', $own->title)
        );
});
