<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): Response {
        return Inertia::render('Admin/Workshop/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response {
        return Inertia::render('Admin/Workshop/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workshop $workshop): Response {
        return Inertia::render('Admin/Workshop/Edit', [
            'workshop' => $workshop
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workshop $workshop) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workshop $workshop) {
        //
    }
}
