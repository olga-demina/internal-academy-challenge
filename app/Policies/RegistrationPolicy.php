<?php

namespace App\Policies;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy {
    public function create(User $user, Workshop $workshop): Response {
        if ($user->role !== 'employee') {
            return Response::deny('Only employees can register for          
  workshops.');
        }

        if ($workshop->registrations()->where('user_id', $user->id)
            ->whereIn('status', [
                RegistrationStatus::Confirmed,
                RegistrationStatus::Waiting
            ])
            ->exists()
        ) {
            return Response::deny('You are already registered for this
  workshop.');
        }

        if ($user->registrations()
            ->whereIn('status', [
                RegistrationStatus::Confirmed,
                RegistrationStatus::Waiting
            ])
            ->whereHas('workshop', fn($q) => $q
                ->where('starts_at', '<', $workshop->ends_at)
                ->where('ends_at', '>', $workshop->starts_at))
            ->exists()
        ) {
            return Response::deny('This workshop overlaps with one you are 
  already attending.');
        }

        return Response::allow();
    }

    public function delete(User $user, Registration $registration): bool {
        return $user->id === $registration->user_id;
    }
}
