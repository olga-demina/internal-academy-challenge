<?php

namespace App\Policies;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

class RegistrationPolicy {
    public function create(User $user, Workshop $workshop): bool {
        if ($user->role !== 'employee') {
            return false;
        }

        $confirmedCount = $workshop->registrations()
            ->where('status', RegistrationStatus::Confirmed)
            ->count();

        if ($confirmedCount >= $workshop->capacity) {
            return false;
        }

        return !$workshop->registrations()
            ->where('user_id', $user->id)
            ->where('status', RegistrationStatus::Confirmed)
            ->exists();
    }

    public function delete(User $user, Registration $registration): bool {
        return $user->id === $registration->user_id
            && $registration->status === RegistrationStatus::Confirmed;
    }
}
