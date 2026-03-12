<?php

namespace App\Http\Controllers;

use App\Models\ActorAssignment;
use Illuminate\Http\Request;

class ActorAssignmentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'assignable_type' => 'required|string',
            'assignable_id' => 'required|integer',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string',
        ]);

        if (empty($data['user_id'])) {
            return back()->withErrors('Vous devez sélectionner un utilisateur valide.');
        }

        ActorAssignment::create($data);

        return back()->with('success', 'Acteur assigné avec succès.');
    }

    public function destroy(ActorAssignment $actorAssignment)
    {
        $actorAssignment->delete();
        return back()->with('success', 'Assignation supprimée avec succès.');
    }
}
