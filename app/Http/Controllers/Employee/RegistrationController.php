<?php

namespace App\Http\Controllers\Employee;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRegistrationRequest;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Workshop;

class RegistrationController extends Controller
{
    public function store(StoreRegistrationRequest $request, Workshop $workshop)
    {
        $workshop->registrations()->create([
            'user_id' => $request->user()->id,
            'status' => RegistrationStatus::Confirmed,
        ]);

        return redirect()->back()->with('success', 'You have successfully registered for this workshop.');
    }

    public function destroy(DeleteRegistrationRequest $request, Workshop $workshop)
    {
        $workshop->registrations()
            ->where('user_id', $request->user()->id)
            ->where('status', RegistrationStatus::Confirmed)
            ->firstOrFail()
            ->delete();

        return redirect()->back();
    }
}
