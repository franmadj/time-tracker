<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\TimeTable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
        $project = Project::where('client_id', $validated['client_id'])->orderBy('order', 'DESC')->first();
        $validated['order'] = $project ? $project->order + 1 : 0;
        $project = Project::create($validated);

        $project->client->active_projects = $project->client->projects->count();
        $project->client->save();

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

        if (!$project->client->period_from) {
            $project->client->period_from = $date->toDateTimeString();
            $project->client->save();
        }
        if (!$project->period_from) {
            $project->period_from = $date->toDateTimeString();
            $project->save();
        }
        $timeTable = $project->times()->create(['started_at' => $date->toDateTimeString()]);

        return response(['success' => true, 'id' => $timeTable->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->time_started = false;
        if ($project->times->count() && !$project->times->last()->ended_at) {
            $project->time_started = (new Carbon($project->times->last()->started_at))->addHours(2)->toDateTimeString();
            $project->time_id = $project->times->last()->id;
        }
        return Inertia::render('Dashboard/TimeTable', [
            'project' => $project,
            'client' => $project->client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        $project->setTotalTime();
        $project->client->setTotalTime();
        return Redirect::route('client.show', $request->client_slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        $client = $project->client;

        $client->active_projects = $client->projects->count();
        $client->save();

        $projectsId = $client->projects()->whereNull('ended_at')->pluck('id');
        
        $timeTable = TimeTable::whereIn('project_id', $projectsId)->orderBy('created_at', 'ASC')->first();
        $client->period_from = $timeTable ? $timeTable->started_at : null;
        $client->save();

        return response(['success' => true]);

        return Redirect::route('client.show', $client->slug);
    }
}
