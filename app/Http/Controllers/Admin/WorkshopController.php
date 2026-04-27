<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModifyWorkshopRequest;
use App\Http\Requests\StoreWorkshopRequest;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response {
        $workshops = $request->user()
            ->workshops()
            ->latest()
            ->paginate(10);
        return Inertia::render('Admin/Workshop/Index', [
            'workshops' => $workshops
        ]);
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
    public function store(StoreWorkshopRequest $request) {
        $request->user()->workshops()->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.workshops.index')
            ->with('success', 'Workshop created successfully.');
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
            'workshop' => $workshop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModifyWorkshopRequest $request, Workshop $workshop) {
        $workshop->update($request->validated());

        return redirect()
            ->route('admin.workshops.index')
            ->with('success', 'Workshop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workshop $workshop) {
        //
    }
}
