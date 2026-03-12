<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Decision;
use App\Models\Domain;
use \Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Affiche l'interface de paramétrage et de prévisualisation des rapports.
     */
    public function index()
    {
        // Données macroscopiques pour l'aperçu
        $totalDecisions = Decision::count();
        $completedDecisions = Decision::where('status', 'completed')->count();
        $delayedDecisions = Decision::where('status', 'delayed')->count();
        
        $averageProgress = Decision::avg('progress_rate') ?? 0;
        
        $domains = Domain::withCount('decisions')->get();

        return view('reports.index', compact(
            'totalDecisions', 'completedDecisions', 'delayedDecisions', 'averageProgress', 'domains'
        ));
    }

    /**
     * Génère et télécharge le rapport stratégique global en PDF.
     */
    public function strategicPdf()
    {
        // Récupération poussée des données
        $decisions = Decision::with(['type', 'institution', 'actionPlans'])->get();
        
        $totalDecisions = $decisions->count();
        $completedDecisions = $decisions->where('status', 'completed')->count();
        $delayedDecisions = $decisions->where('status', 'delayed')->count();
        
        // Calcul manuel pour la présentation
        $avgProgress = $decisions->count() > 0
                ? $decisions->avg('progress_rate')
                : 0;

        $domains = Domain::with(['decisions' => function($q) {
            $q->with('actionPlans');
        }])->get();

        $priorityDecisions = Decision::where('priority', 1)
                                    ->orWhere('status', 'delayed')
                                    ->with('type')
                                    ->take(10)
                                    ->get();

        $pdf = Pdf::loadView('reports.pdf.strategic', compact(
            'totalDecisions', 'completedDecisions', 'delayedDecisions', 'avgProgress', 'domains', 'priorityDecisions', 'decisions'
        ));

        // Format paysage pour de grands tableaux possibles, ou portrait
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('rapport_strategique_ceeac_' . date('Y_m_d') . '.pdf');
    }
}
