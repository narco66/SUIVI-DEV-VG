<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\Organ;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('organ')->latest()->paginate(15);
        $organs = Organ::all();
        return view('sessions.index', compact('sessions', 'organs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organ_id' => 'required|exists:organs,id',
            'code' => 'required|string|max:50',
            'type' => 'required|string|in:ordinaire,extraordinaire',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|in:planned,ongoing,completed,cancelled',
        ]);
        Session::create($validated);
        return redirect()->route('sessions.index')->with('success', 'Session ajoutée avec succès.');
    }

    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'organ_id' => 'required|exists:organs,id',
            'code' => 'required|string|max:50',
            'type' => 'required|string|in:ordinaire,extraordinaire',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|in:planned,ongoing,completed,cancelled',
        ]);
        $session->update($validated);
        return redirect()->route('sessions.index')->with('success', 'Session modifiée avec succès.');
    }

    public function destroy(Session $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Session supprimée avec succès.');
    }
}
