<?php

namespace App\Http\Controllers;

use App\Models\timeTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TimeTableController extends Controller
{

    /**
     * Update the specified resource in storage.
     */
    public function startTime(Request $request, timeTable $timeTable)
    {
        $date = new Carbon($request->startedAt);
        //dd($date->toDateTimeString());
        $timeTable = $project->times()->create(['started_at' => $date->toDateTimeString()]);
        return response(['success' => true, 'id' => $timeTable->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function stopTime(Request $request, timeTable $timeTable)
    {
        $date = new Carbon($request->stoppedAt);
        //dd($date->toDateTimeString());
        $timeTable->update(['ended_at' => $date->toDateTimeString()]);
        $timeTable->project->setTotalTime();
        $timeTable->project->client->setTotalTime();
        return response(['success' => true, 'id' => $timeTable->id, 'project' => $timeTable->project]);
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
    public function destroy(timeTable $timeTable)
    {
        $timeTable->delete();
        $timeTable->project->setTotalTime();
        return Redirect::route('project.show', $timeTable->project->id);
    }
}
