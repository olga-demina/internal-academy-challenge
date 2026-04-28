<?php

namespace App\Console\Commands;

use App\Enums\RegistrationStatus;
use App\Mail\WorkshopReminder;
use App\Models\Workshop;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('academy:remind')]
#[Description('Send reminder emails to participants of workshops 
  scheduled for tomorrow')]
class SendWorkshopReminders extends Command {
    /**
     * Execute the console command.
     */
    public function handle(): void {
        $tomorrow = now()->addDay();

        $workshops = Workshop::whereBetween('starts_at', [
            $tomorrow->startOfDay(),
            $tomorrow->copy()->endOfDay(),
        ])->get();

        if ($workshops->isEmpty()) {
            $this->info('No workshops scheduled for tomorrow.');
            return;
        }

        foreach ($workshops as $workshop) {
            $users = $workshop->registrations()
                ->where('status', RegistrationStatus::Confirmed)
                ->with('user')
                ->get()
                ->pluck('user');

            foreach ($users as $user) {
                Mail::to($user->email)->send(new
                    WorkshopReminder($workshop, $user));
                $this->line("  Sent to {$user->email} for
  \"{$workshop->title}\"");
            }
        }

        $this->info('Done.');
    }
}
