<?php

namespace App\Observers;

use App\Enums\RegistrationStatus;
use App\Models\Registration;

class RegistrationObserver {
    public function deleted(Registration $registration): void {
        if ($registration->status !== RegistrationStatus::Confirmed) {
            return;
        }

        Registration::where('workshop_id', $registration->workshop_id)
            ->where('status', RegistrationStatus::Waiting)
            ->orderBy('id')
            ->first()
            ?->update([
                'status'      => RegistrationStatus::Confirmed,
                'promoted_at' => now(),
            ]);
    }
}
