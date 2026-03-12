<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'title' => 'required|string|max:255',
            'expected_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        if ($data['status'] == 'achieved' && empty($data['achieved_date'])) {
            $data['achieved_date'] = now();
        }

        Milestone::create($data);

        return redirect()->route('activities.show', $data['activity_id'])
            ->with('success', 'Jalon ajouté avec succès.');
    }

    public function destroy(Milestone $milestone)
    {
        $activityId = $milestone->activity_id;
        $milestone->delete();

        return redirect()->route('activities.show', $activityId)
            ->with('success', 'Jalon supprimé.');
    }
}
