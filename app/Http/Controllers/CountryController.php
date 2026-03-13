<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::latest()->paginate(15);
        return view('countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code_iso' => 'required|string|max:10',
            'region' => 'nullable|string|max:100',
        ]);
        Country::create($validated);
        return redirect()->route('countries.index')->with('success', 'Pays ajouté avec succès.');
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code_iso' => 'required|string|max:10',
            'region' => 'nullable|string|max:100',
        ]);
        $country->update($validated);
        return redirect()->route('countries.index')->with('success', 'Pays modifié avec succès.');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.index')->with('success', 'Pays supprimé avec succès.');
    }
}