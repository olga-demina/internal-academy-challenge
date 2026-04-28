<?php

namespace App\Http\Controllers\Employee;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
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
}
