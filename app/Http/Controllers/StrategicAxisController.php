<?php

namespace App\Http\Controllers;

use App\Models\StrategicAxis;
use App\Models\ActionPlan;
use App\Http\Requests\StrategicAxisRequest;
use Illuminate\Http\Request;

class StrategicAxisController extends Controller
{
    public function create(Request $request)
    {
        $planId = $request->get('action_plan_id');
        $actionPlan = $planId ? ActionPlan::findOrFail($planId) : null;
        $actionPlans = ActionPlan::all();
        
        return view('strategic_axes.create', compact('actionPlan', 'actionPlans'));
    }

    public function store(StrategicAxisRequest $request)
    {
        $data = $request->validated();
        if (empty($data['code'])) {
            $data['code'] = 'AXE-' . rand(100, 999);
        }
        
        $axis = StrategicAxis::create($data);

        return redirect()->route('action-plans.show', $axis->action_plan_id)
            ->with('success', 'Axe stratégique ajouté avec succès.');
    }

    public function edit(StrategicAxis $strategicAxis)
    {
        $actionPlans = ActionPlan::all();
        return view('strategic_axes.edit', compact('strategicAxis', 'actionPlans'));
    }

    public function update(StrategicAxisRequest $request, StrategicAxis $strategicAxis)
    {
        $strategicAxis->update($request->validated());

        return redirect()->route('action-plans.show', $strategicAxis->action_plan_id)
            ->with('success', 'Axe stratégique mis à jour avec succès.');
    }

    public function destroy(StrategicAxis $strategicAxis)
    {
        $planId = $strategicAxis->action_plan_id;
        $strategicAxis->delete();

        return redirect()->route('action-plans.show', $planId)
            ->with('success', 'Axe stratégique supprimé avec succès.');
    }
}
