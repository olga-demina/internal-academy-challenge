<?php

namespace App\Http\Controllers\Employee;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller {
    public function index(Request $request): Response {
        $workshops = Workshop::withAvailableSeats()
            ->withUserRegistration($request->user()->id)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get(['id', 'title', 'description', 'starts_at', 'ends_at', 'capacity', 'registration_status']);

        return Inertia::render('Employee/Workshop/Index', [
            'workshops' => $workshops,
        ]);
    }
}
