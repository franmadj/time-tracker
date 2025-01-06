<?php

namespace App\Http\Controllers;

use App\Models\TimeTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TimeTableController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function stopTime(Request $request, TimeTable $timeTable)
    {
        $date = new Carbon($request->stoppedAt);
        //dd($date->toDateTimeString());
        $timeTable->update(['ended_at' => $date->toDateTimeString()]);
        $timeTable->project->setTotalTime();
        $timeTable->project->client->setTotalTime();
        return response(['success' => true, 'id' => $timeTable->id, 'project' => $timeTable->project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeTable $timeTable)
    {
        $timeTable->delete();
        $timeTable->project->setTotalTime();
        return Redirect::route('project.show', $timeTable->project->id);
    }
}
