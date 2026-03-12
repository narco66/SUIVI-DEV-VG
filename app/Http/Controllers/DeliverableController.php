<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use Illuminate\Http\Request;

class DeliverableController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expected_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        Deliverable::create($data);

        return redirect()->route('activities.show', $data['activity_id'])
            ->with('success', 'Livrable ajouté avec succès.');
    }

    public function destroy(Deliverable $deliverable)
    {
        $activityId = $deliverable->activity_id;
        $deliverable->delete();

        return redirect()->route('activities.show', $activityId)
            ->with('success', 'Livrable supprimé.');
    }
}
