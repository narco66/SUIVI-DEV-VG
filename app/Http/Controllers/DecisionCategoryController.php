<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionCategory;

class DecisionCategoryController extends Controller
{
    public function index()
    {
        $categories = DecisionCategory::latest()->paginate(15);
        return view('decision_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        DecisionCategory::create($validated);
        return redirect()->route('decision-categories.index')->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function update(Request $request, DecisionCategory $decisionCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $decisionCategory->update($validated);
        return redirect()->route('decision-categories.index')->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(DecisionCategory $decisionCategory)
    {
        $decisionCategory->delete();
        return redirect()->route('decision-categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}