<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::with('parent')->latest()->paginate(15);
        $parentDomains = Domain::whereNull('parent_id')->get();
        return view('domains.index', compact('domains', 'parentDomains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:domains,id',
        ]);
        Domain::create($validated);
        return redirect()->route('domains.index')->with('success', 'Domaine ajouté avec succès.');
    }

    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:domains,id',
        ]);
        $domain->update($validated);
        return redirect()->route('domains.index')->with('success', 'Domaine modifié avec succès.');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('domains.index')->with('success', 'Domaine supprimé avec succès.');
    }
}
