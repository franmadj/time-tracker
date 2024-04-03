<?php

namespace App\Http\Controllers;



use App\Models\timeTable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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
