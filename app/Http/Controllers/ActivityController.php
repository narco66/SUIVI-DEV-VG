<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Action;
use App\Models\Direction;
use App\Http\Requests\ActivityRequest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function create(Request $request)
    {
        $actionId = $request->get('action_id');
        $actionData = $actionId ? Action::findOrFail($actionId) : null;
        $actions = Action::with('strategicAxis.actionPlan')->get();
        $directions = Direction::orderBy('name')->get();
        
        return view('activities.create', compact('actionData', 'actions', 'directions'));
    }

    public function store(ActivityRequest $request)
    {
        $activity = Activity::create($request->validated());

        return redirect()->route('action-plans.show', $activity->action->strategicAxis->action_plan_id)
            ->with('success', 'Activité ajoutée avec succès.');
    }

    public function show(Activity $activity)
    {
        $activity->load(['action.strategicAxis.actionPlan', 'direction', 'milestones', 'deliverables', 'progressUpdates.user', 'indicators']);
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $actions = Action::with('strategicAxis.actionPlan')->get();
        $directions = Direction::orderBy('name')->get();
        
        return view('activities.edit', compact('activity', 'actions', 'directions'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        return redirect()->route('activities.show', $activity->id)
            ->with('success', 'Activité mise à jour avec succès.');
    }

    public function destroy(Activity $activity)
    {
        $planId = $activity->action->strategicAxis->action_plan_id;
        $activity->delete();

        return redirect()->route('action-plans.show', $planId)
            ->with('success', 'Activité supprimée avec succès.');
    }
}
