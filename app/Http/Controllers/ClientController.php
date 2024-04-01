<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth()->user()->id;
        $validated['slug'] = Str::slug($validated['name'], "-");
        Client::create($validated);
        return Redirect::route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return Inertia::render('Dashboard/ClientProjects', [
            'projects' => $client->projects()->orderBy('order', 'DESC')->get(),
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setPriorory(Client $client, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prioroty' => 'required|in:low,middle,high',
        ]);
        $client->update($validated);
        return Redirect::route('client.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setOrder(Request $request) : ResponseFactory
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
        $validated = $request->validated();
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
