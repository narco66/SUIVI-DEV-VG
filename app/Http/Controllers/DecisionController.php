<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Session;
use App\Models\DecisionType;
use App\Models\DecisionCategory;
use App\Models\Domain;
use App\Models\Institution;
use App\Http\Requests\DecisionRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DecisionExport;

class DecisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Decision::with(['type', 'category', 'domain', 'session', 'institution']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $decisions = $query->latest()->paginate(10)->withQueryString();
        
        return view('decisions.index', compact('decisions'));
    }

    public function create()
    {
        $sessions = Session::all();
        $types = DecisionType::all();
        $categories = DecisionCategory::all();
        $domains = Domain::all();
        $institutions = Institution::all();

        return view('decisions.create', compact('sessions', 'types', 'categories', 'domains', 'institutions'));
    }

    public function store(DecisionRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id() ?? 1; // Fallback for dev if needed
        $data['code'] = $data['code'] ?? 'DEC-' . date('Y') . '-' . strtoupper(uniqid());

        Decision::create($data);

        return redirect()->route('decisions.index')
            ->with('success', 'Décision enregistrée avec succès.');
    }

    public function show(Decision $decision)
    {
        $decision->load(['type', 'category', 'domain', 'session', 'institution', 'creator', 'documents', 'actorAssignments.user', 'actorAssignments.institution', 'actionPlans']);
        $users = \App\Models\User::all(); // On a besoin des utilisateurs pour le formulaire d'assignation
        return view('decisions.show', compact('decision', 'users'));
    }

    public function edit(Decision $decision)
    {
        $sessions = Session::all();
        $types = DecisionType::all();
        $categories = DecisionCategory::all();
        $domains = Domain::all();
        $institutions = Institution::all();

        return view('decisions.edit', compact('decision', 'sessions', 'types', 'categories', 'domains', 'institutions'));
    }

    public function update(DecisionRequest $request, Decision $decision)
    {
        $decision->update($request->validated());

        return redirect()->route('decisions.index')
            ->with('success', 'Décision mise à jour avec succès.');
    }

    public function destroy(Decision $decision)
    {
        $decision->delete();

        return redirect()->route('decisions.index')
            ->with('success', 'Décision supprimée avec succès.');
    }

    public function export(Request $request)
    {
        return Excel::download(new DecisionExport($request->search, $request->status), 'decisions_' . date('Y_m_d') . '.xlsx');
    }
}
