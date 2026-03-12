<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Decision;
use App\Models\ProgressUpdate;
use App\Models\Domain;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Indicateurs clés (KPIs)
        $totalDecisions = Decision::count();
        $globalExecutionRate = $totalDecisions > 0 ? Decision::avg('progress_rate') : 0;
        
        $delayedDecisions = Decision::where('date_echeance', '<', now())
                                    ->where('progress_rate', '<', 100)
                                    ->count();
                                    
        $highPriorityDelayed = Decision::where('date_echeance', '<', now())
                                    ->where('progress_rate', '<', 100)
                                    ->where('priority', 1)
                                    ->count();

        $pendingValidations = ProgressUpdate::where('status', 'pending')->count();

        // Activités Récentes (5 derniers rapports d'avancement soumis)
        $recentActivities = ProgressUpdate::with(['author', 'updatable'])
                                          ->latest('submitted_at')
                                          ->take(5)
                                          ->get();

        // Données pour le Graphique (Performances par Domaine)
        $domains = Domain::with('decisions')->get();
        $chartLabels = [];
        $chartData = [];
        
        foreach ($domains as $domain) {
            if ($domain->decisions->count() > 0) {
                $chartLabels[] = $domain->name;
                $chartData[] = round($domain->decisions->avg('progress_rate'), 2);
            }
        }

        return view('home', compact(
            'globalExecutionRate', 
            'delayedDecisions', 
            'highPriorityDelayed',
            'pendingValidations', 
            'recentActivities',
            'chartLabels',
            'chartData'
        ));
    }
}
