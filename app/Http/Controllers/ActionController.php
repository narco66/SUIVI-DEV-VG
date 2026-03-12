<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\StrategicAxis;
use App\Http\Requests\ActionRequest;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function create(Request $request)
    {
        $axisId = $request->get('strategic_axis_id');
        $axis = $axisId ? StrategicAxis::with('actionPlan')->findOrFail($axisId) : null;
        $axes = StrategicAxis::with('actionPlan')->get();
        
        return view('actions.create', compact('axis', 'axes'));
    }

    public function store(ActionRequest $request)
    {
        $data = $request->validated();
        if (empty($data['code'])) {
            $data['code'] = 'ACT-' . rand(100, 999);
        }
        
        $action = Action::create($data);

        return redirect()->route('action-plans.show', $action->strategicAxis->action_plan_id)
            ->with('success', 'Action ajoutée avec succès.');
    }

    public function edit(Action $action)
    {
        $axes = StrategicAxis::with('actionPlan')->get();
        return view('actions.edit', compact('action', 'axes'));
    }

    public function update(ActionRequest $request, Action $action)
    {
        $action->update($request->validated());

        return redirect()->route('action-plans.show', $action->strategicAxis->action_plan_id)
            ->with('success', 'Action mise à jour avec succès.');
    }

    public function destroy(Action $action)
    {
        $planId = $action->strategicAxis->action_plan_id;
        $action->delete();

        return redirect()->route('action-plans.show', $planId)
            ->with('success', 'Action supprimée avec succès.');
    }
}
