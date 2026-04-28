<?php

namespace App\Http\Controllers\Employee;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRegistrationRequest;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Workshop;

class RegistrationController extends Controller {
    public function store(StoreRegistrationRequest $request, Workshop $workshop) {
        $availableSeats = $workshop->capacity - $workshop->registrations()
            ->where('status', RegistrationStatus::Confirmed)
            ->count();

        $status = $availableSeats > 0
            ? RegistrationStatus::Confirmed
            : RegistrationStatus::Waiting;

        $workshop->registrations()->create([
            'user_id' => $request->user()->id,
            'status'  => $status,
        ]);

        return redirect()->back()->with(
            'success',
            $status ===
                RegistrationStatus::Confirmed
                ? 'You have successfully registered for this workshop.'
                : 'The workshop is full. You have been added to the waitlist.'
        );
    }

    public function destroy(DeleteRegistrationRequest $request, Workshop $workshop) {
        $registration = $workshop->registrations()
            ->where('user_id', $request->user()->id)
            ->whereIn('status', [RegistrationStatus::Confirmed, RegistrationStatus::Waiting])
            ->firstOrFail();

        $message = $registration->status === RegistrationStatus::Waiting
            ? "You have left the waitlist for \"{$workshop->title}\"."
            : "Your registration for \"{$workshop->title}\" has been cancelled.";

        $registration->delete();

        return redirect()->back()->with('success', $message);
    }
}
