<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
    public function index(): Response
    {
        $workshops = Workshop::withAvailableSeats()
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get(['id', 'title', 'description', 'starts_at', 'ends_at', 'capacity']);

        return Inertia::render('Employee/Workshop/Index', [
            'workshops' => $workshops,
        ]);
    }
}
