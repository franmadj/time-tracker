<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return Redirect::route('client.show', $request->client_slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setPriorory(Project $project, Request $request)
    {
        $validated = $request->validate([
            'prioroty' => 'required|in:low,middle,high',
        ]);
        $project->update($validated);
        return response(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setOrder(Request $request)
    {
        foreach ($request->projectsOrder as $projectOrder) {
            if ($project = Project::find($projectOrder['id'])) {
                $project->update(['order' => $projectOrder['order']]);
            }
        }
        return response(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function startTime(Request $request, Project $project)
    {
        $date = new Carbon($request->startedAt);
        //dd($date->toDateTimeString());
        $timeTable = $project->times()->create(['started_at' => $date->toDateTimeString()]);
        return response(['success' => true, 'id' => $timeTable->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function stopTime(Request $request, Project $project)
    {
        $date = new Carbon($request->stoppedAt);
        //dd($date->toDateTimeString());
        $timeTable = $project->times()->create(['started_at' => $date->toDateTimeString()]);
        return response(['success' => true, 'id' => $timeTable->id]);
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
        $project->update($validated);
        return Redirect::route('client.show', $request->client_slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return Redirect::route('client.show', $project->client->slug);
    }
}
