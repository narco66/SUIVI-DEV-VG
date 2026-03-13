<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionType;

class DecisionTypeController extends Controller
{
    public function index()
    {
        $types = DecisionType::latest()->paginate(15);
        return view('decision_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        DecisionType::create($validated);
        return redirect()->route('decision-types.index')->with('success', 'Type de décision ajouté avec succès.');
    }

    public function update(Request $request, DecisionType $decisionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $decisionType->update($validated);
        return redirect()->route('decision-types.index')->with('success', 'Type de décision modifié avec succès.');
    }

    public function destroy(DecisionType $decisionType)
    {
        $decisionType->delete();
        return redirect()->route('decision-types.index')->with('success', 'Type de décision supprimé avec succès.');
    }
}