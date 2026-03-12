<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use App\Models\ProgressUpdate;
use App\Http\Requests\ValidationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    public function index()
    {
        // Get progress updates pending validation
        $pendingUpdates = ProgressUpdate::with(['updatable', 'user.institution'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);
            
        // Get recent validations history
        $recentValidations = Validation::with(['progressUpdate.updatable', 'validator', 'progressUpdate.user'])
            ->where('validator_id', Auth::id() ?? 1)
            ->latest('validated_at')
            ->take(10)
            ->get();
            
        return view('validations.index', compact('pendingUpdates', 'recentValidations'));
    }

    public function store(ValidationRequest $request)
    {
        $data = $request->validated();
        $data['validator_id'] = Auth::id() ?? 1;
        $data['validated_at'] = now();

        DB::transaction(function () use ($data) {
            // Créer la trace de validation
            Validation::create($data);

            // Mettre à jour le statut du rapport parent
            $progressUpdate = ProgressUpdate::findOrFail($data['update_id']);
            $progressUpdate->update([
                'status' => $data['status'] == 'approved' ? 'validated' : 'rejected'
            ]);
            
            // Si approuvé, appliquer le taux d'avancement au modèle cible (Action, Activity, etc.)
            if ($data['status'] == 'approved') {
                $updatableModel = $progressUpdate->updatable;
                if ($updatableModel && in_array('progress_rate', $updatableModel->getFillable())) {
                    $updatableModel->update([
                        'progress_rate' => $progressUpdate->progress_rate
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Rapport d\'avancement ' . ($data['status'] == 'approved' ? 'validé' : 'rejeté') . '.');
    }
}
