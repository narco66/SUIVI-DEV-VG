<?php

namespace App\Http\Controllers;

use App\Models\ActionPlan;
use App\Models\Decision;
use App\Models\Direction;
use App\Http\Requests\ActionPlanRequest;
use Illuminate\Http\Request;

class ActionPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionPlan::with('decision');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('decision_id')) {
            $query->where('decision_id', $request->decision_id);
        }

        $actionPlans = $query->latest()->paginate(10)->withQueryString();
        $decisions = Decision::orderBy('code')->get();

        return view('action_plans.index', compact('actionPlans', 'decisions'));
    }

    public function create(Request $request)
    {
        $decisions = Decision::orderBy('code')->get();
        $selectedDecision = $request->get('decision_id');
        
        return view('action_plans.create', compact('decisions', 'selectedDecision'));
    }

    public function store(ActionPlanRequest $request)
    {
        ActionPlan::create($request->validated());

        return redirect()->route('action-plans.index')
            ->with('success', 'Plan d\'action créé avec succès.');
    }

    public function show(ActionPlan $actionPlan)
    {
        $actionPlan->load(['decision', 'strategicAxes.actions.activities']);
        $directions = Direction::orderBy('name')->get();
        return view('action_plans.show', compact('actionPlan', 'directions'));
    }

    public function edit(ActionPlan $actionPlan)
    {
        $decisions = Decision::orderBy('code')->get();
        return view('action_plans.edit', compact('actionPlan', 'decisions'));
    }

    public function update(ActionPlanRequest $request, ActionPlan $actionPlan)
    {
        $actionPlan->update($request->validated());

        return redirect()->route('action-plans.index')
            ->with('success', 'Plan d\'action mis à jour avec succès.');
    }

    public function destroy(ActionPlan $actionPlan)
    {
        $actionPlan->delete();

        return redirect()->route('action-plans.index')
            ->with('success', 'Plan d\'action supprimé avec succès.');
    }
}
