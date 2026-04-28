<?php

namespace App\Observers;

use App\Enums\RegistrationStatus;
use App\Events\RegistrationCountChanged;
use App\Models\Registration;

class RegistrationObserver {
    public function created(Registration $registration): void {
        $this->broadcastCount($registration);
    }

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

        $this->broadcastCount($registration);
    }

    private function broadcastCount(Registration $registration): void {
        $adminId = $registration->workshop->user_id;

        $total = Registration::confirmedForAdmin($adminId)->count();

        try {
            RegistrationCountChanged::dispatch($adminId, $total);
        } catch (\Illuminate\Broadcasting\BroadcastException $e) {
            // Broadcasting server unavailable — non-critical, skip silently
        }
    }
}
