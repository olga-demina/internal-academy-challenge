<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $mostPopular = $request->user()
            ->workshops()
            ->withRegistrationCounts()
            ->orderByDesc('total_registrations_count')
            ->first();

        return Inertia::render('Admin/Dashboard', [
            'mostPopular' => $mostPopular ? [
                'title'       => $mostPopular->title,
                'starts_at'   => $mostPopular->starts_at,
                'ends_at'     => $mostPopular->ends_at,
                'capacity'    => $mostPopular->capacity,
                'total'       => $mostPopular->total_registrations_count,
                'confirmed'   => $mostPopular->confirmed_registrations_count,
                'waiting'     => $mostPopular->waiting_registrations_count,
            ] : null,
        ]);
    }
}
