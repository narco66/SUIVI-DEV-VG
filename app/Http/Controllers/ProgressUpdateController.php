<?php

namespace App\Http\Controllers;

use App\Models\ProgressUpdate;
use App\Models\Action;
use App\Models\Activity;
use App\Models\ActionPlan;
use App\Models\Decision;
use App\Http\Requests\ProgressUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgressUpdateController extends Controller
{
    public function index(Request $request)
    {
        $updates = ProgressUpdate::with('author', 'updatable')->latest()->paginate(15)->withQueryString();
        return view('progress_updates.index', compact('updates'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        
        if (!$type || !$id) {
            return redirect()->back()->withErrors('Modèle ou identifiant manquant.');
        }

        $modelClass = match ($type) {
            'action' => Action::class,
            'activity' => Activity::class,
            'action_plan' => ActionPlan::class,
            'decision' => Decision::class,
            default => null,
        };

        if (!$modelClass) {
            abort(404);
        }

        $model = call_user_func([$modelClass, 'findOrFail'], $id);
        
        return view('progress_updates.create', compact('model', 'modelClass'));
    }

    public function store(ProgressUpdateRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = Auth::id() ?? 1; // Fallback pour les seed / tests sans connexion
        $data['status'] = 'pending';
        $data['submitted_at'] = now();
        
        ProgressUpdate::create($data);
        
        // Mise à jour de la jauge sur le modèle parent
        $modelClass = $data['updatable_type'];
        $model = call_user_func([$modelClass, 'findOrFail'], $data['updatable_id']);
        
        // On ne met PLUS à jour la jauge immédiatement à la soumission
        // La mise à jour du taux d'avancement se fera uniquement à la validation
        /*
        if (in_array('progress_rate', $model->getFillable())) {
            $model->update(['progress_rate' => $data['progress_rate']]);
        }
        */

        return redirect()->route('progress-updates.index')
            ->with('success', 'Rapport d\'avancement soumis avec succès.');
    }

    public function show(ProgressUpdate $progressUpdate)
    {
        $progressUpdate->load(['author', 'updatable', 'validations.validator']);
        return view('progress_updates.show', compact('progressUpdate'));
    }

    public function exportPdf(ProgressUpdate $progressUpdate)
    {
        $progressUpdate->load(['author', 'updatable', 'validations.validator']);
        
        $pdf = Pdf::loadView('progress_updates.pdf', ['update' => $progressUpdate]);
        
        return $pdf->download('Rapport_Avancement_' . $progressUpdate->id . '_' . date('Ymd') . '.pdf');
    }
}
