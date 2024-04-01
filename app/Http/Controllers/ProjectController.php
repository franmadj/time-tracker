<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Project::create($validated);
        return Redirect::route('client.show', $request->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setPriorory(Project $project, Request $request)
    {
        $validated = $request->validate([
            'prioroty' => 'required|in:low,middle,high',
        ]);
        $client->update($validated);
        return response(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setOrder(Request $request)
    {
        foreach ($request->clientsOrder as $clientOrder) {
            if ($client = Client::find($clientOrder['id'])) {
                $client->update(['order' => $clientOrder['order']]);
            }
        }
        return response(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name'], "-");
        $client->update($validated);
        return Redirect::to('/client');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $client->delete();
        return Redirect::to('/client');
    }
}
