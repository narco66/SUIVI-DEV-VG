<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use App\Models\Country;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::with('country')->latest()->paginate(15);
        $countries = Country::orderBy('name')->get();
        return view('institutions.index', compact('institutions', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'type_id' => 'required|in:ceeac,etat_membre,partenaire',
            'country_id' => 'nullable|exists:countries,id',
        ]);
        Institution::create($validated);
        return redirect()->route('institutions.index')->with('success', 'Institution ajoutée avec succès.');
    }

    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'type_id' => 'required|in:ceeac,etat_membre,partenaire',
            'country_id' => 'nullable|exists:countries,id',
        ]);
        $institution->update($validated);
        return redirect()->route('institutions.index')->with('success', 'Institution modifiée avec succès.');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();
        return redirect()->route('institutions.index')->with('success', 'Institution supprimée avec succès.');
    }
}