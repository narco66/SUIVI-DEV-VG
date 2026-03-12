<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organ;

class OrganController extends Controller
{
    public function index()
    {
        $organs = Organ::with('parent')->latest()->paginate(15);
        $parentOrgans = Organ::whereNull('parent_id')->get();
        return view('organs.index', compact('organs', 'parentOrgans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'level' => 'nullable|integer',
            'parent_id' => 'nullable|exists:organs,id',
        ]);
        Organ::create($validated);
        return redirect()->route('organs.index')->with('success', 'Organe ajouté avec succès.');
    }

    public function update(Request $request, Organ $organ)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'level' => 'nullable|integer',
            'parent_id' => 'nullable|exists:organs,id',
        ]);
        $organ->update($validated);
        return redirect()->route('organs.index')->with('success', 'Organe modifié avec succès.');
    }

    public function destroy(Organ $organ)
    {
        $organ->delete();
        return redirect()->route('organs.index')->with('success', 'Organe supprimé avec succès.');
    }
}
