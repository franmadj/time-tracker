<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Clients', [
            'clients' => Client::orderBy('order', 'ASC')->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $validated            = $request->validated();
        $validated['user_id'] = Auth()->user()->id;
        $validated['slug']    = Str::slug($validated['name'], "-");
        Client::create($validated);
        return Redirect::route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $projects = $client->projects()->with('times')->orderBy('order', 'ASC');
        $term     = request('term') ?: '';
        if (strlen($term)) {
            $projects = $projects->where('name', 'like', '%' . $term . '%');
        }

        $projects = $projects->get()->map(function (Model $project, int $key) {
            $project->time_started = false;
            if ($project->times->count() && ! $project->times->last()->ended_at) {
                $project->time_started = (new Carbon($project->times->last()->started_at))->addHours(2)->toDateTimeString();
                $project->time_id      = $project->times->last()->id;
            }
            return $project;
        });

        return Inertia::render('Dashboard/Projects', [
            'projects'   => $projects,
            'client'     => $client,
            'searchTerm' => $term,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setPriorory(Client $client, Request $request)
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
    public function updateNotes(Client $client, Request $request)
    {
        $client->notes = $request->content;
        $client->save();
        return response(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setOrder(Request $request): HttpResponse
    {
        foreach ($request->clientsOrder as $clientOrder) {
            if ($client = Client::find($clientOrder['id'])) {
                $client->update(['order' => $clientOrder['order']]);
            }
        }
        return response(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $validated         = $request->validated();
        $validated['slug'] = Str::slug($validated['name'], "-");
        $client->update($validated);
        return Redirect::to('/client');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        return Redirect::to('/client');
    }
}
