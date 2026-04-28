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

        return !$workshop->registrations()
            ->where('user_id', $user->id)
            ->whereIn('status', [RegistrationStatus::Confirmed, RegistrationStatus::Waiting])
            ->exists();
    }

    public function delete(User $user, Registration $registration): bool {
        return $user->id === $registration->user_id;
    }
}
