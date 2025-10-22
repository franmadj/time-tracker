<?php

namespace App\Http\Controllers;

use App\Models\Shortcut;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ShortcutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $shortcuts = auth()->user()->shortcuts()->orderBy('order')->get();
        
        return Inertia::render('Dashboard/Shortcuts', [
            'shortcuts' => $shortcuts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'color' => 'required|string|max:12',
            'icon' => 'required|string|max:50',
        ]);

        $maxOrder = auth()->user()->shortcuts()->max('order') ?? 0;

        auth()->user()->shortcuts()->create([
            ...$validated,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->back()->with('success', 'Shortcut created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shortcut $shortcut): RedirectResponse
    {
        // Ensure user owns this shortcut
        if ($shortcut->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'color' => 'required|string|max:12',
            'icon' => 'required|string|max:50',
        ]);

        $shortcut->update($validated);

        return redirect()->back()->with('success', 'Shortcut updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortcut $shortcut): RedirectResponse
    {
        // Ensure user owns this shortcut
        if ($shortcut->user_id !== auth()->id()) {
            abort(403);
        }

        $shortcut->delete();

        return redirect()->back()->with('success', 'Shortcut deleted successfully.');
    }

    /**
     * Update the ordering of shortcuts.
     */
    public function updateOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shortcuts' => 'required|array',
            'shortcuts.*.id' => 'required|integer|exists:shortcuts,id',
            'shortcuts.*.order' => 'required|integer',
        ]);

        foreach ($validated['shortcuts'] as $shortcutData) {
            $shortcut = Shortcut::find($shortcutData['id']);
            
            // Ensure user owns this shortcut
            if ($shortcut && $shortcut->user_id === auth()->id()) {
                $shortcut->update(['order' => $shortcutData['order']]);
            }
        }

        return redirect()->back()->with('success', 'Shortcuts reordered successfully.');
    }
}
