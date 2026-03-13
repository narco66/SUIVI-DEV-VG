<?php

namespace App\Http\Controllers;

use App\Models\ActionPlan;
use App\Models\Activity;
use App\Models\AiAnalysis;
use App\Models\Decision;
use App\Models\DecisionCategory;
use App\Models\DecisionType;
use App\Models\Deliverable;
use App\Models\Milestone;
use App\Models\StrategicAxis;
use App\Models\Action;
use App\Services\DecisionAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiAnalysisController extends Controller
{
    /** Formulaire de lancement d'une nouvelle analyse IA. */
    public function create()
    {
        return view('ai_analyses.create');
    }

    /** Soumettre le texte à analyser par l'IA. */
    public function analyze(Request $request, DecisionAnalysisService $analysisService)
    {
        $request->validate([
            'source_text'        => 'required|string|min:50|max:80000',
            'document_type'      => 'required|string|max:100',
            'document_title'     => 'nullable|string|max:500',
            'document_reference' => 'nullable|string|max:255',
        ], [
            'source_text.required'   => 'Le texte du document est obligatoire.',
            'source_text.min'        => 'Le texte doit contenir au moins 50 caractères.',
            'source_text.max'        => 'Le texte ne peut pas dépasser 80 000 caractères.',
            'document_type.required' => 'Le type d\'acte est obligatoire.',
        ]);

        try {
            $analysis = $analysisService->analyzeDocument(
                $request->input('source_text'),
                $request->input('document_type'),
                $request->input('document_title'),
                $request->input('document_reference')
            );

            if ($analysis->status === 'error') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de l\'analyse : ' . ($analysis->raw_response['error'] ?? 'Erreur inconnue'));
            }

            return redirect()
                ->route('ai-analyses.show', $analysis->id)
                ->with('success', 'Analyse générée avec succès. Vérifiez la structure avant importation.');
        } catch (\Exception $e) {
            Log::error('AiAnalysisController::analyze error', ['msg' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur interne est survenue : ' . $e->getMessage());
        }
    }

    /** Prévisualisation de l'analyse. */
    public function show(AiAnalysis $aiAnalysis)
    {
        // Seul l'auteur ou un admin peut voir l'analyse
        if ($aiAnalysis->user_id && $aiAnalysis->user_id !== Auth::id()) {
            abort_unless(
                Auth::user()?->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier']),
                403,
                'Vous n\'êtes pas autorisé à consulter cette analyse.'
            );
        }

        $data = $aiAnalysis->structured_data ?? [];

        return view('ai_analyses.show', compact('aiAnalysis', 'data'));
    }

    /** Confirmer l'analyse et créer l'arborescence en base. */
    public function confirm(Request $request, AiAnalysis $aiAnalysis)
    {
        // Seul l'auteur ou un admin peut confirmer
        if ($aiAnalysis->user_id && $aiAnalysis->user_id !== Auth::id()) {
            abort_unless(
                Auth::user()?->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier']),
                403
            );
        }

        if ($aiAnalysis->status === 'finalized') {
            return redirect()->route('home')->with('info', 'Cette analyse a déjà été convertie en arborescence.');
        }

        // Tentative de récupérer un JSON éventuellement modifié côté client
        $data = null;
        $editedJsonRaw = $request->input('edited_json');
        if (!empty($editedJsonRaw)) {
            $decoded = json_decode($editedJsonRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data = $decoded;
            } else {
                Log::warning('AiAnalysisController::confirm — JSON invalide soumis', [
                    'error' => json_last_error_msg(),
                ]);
            }
        }

        // Fallback vers les données originales
        if (!$data) {
            $data = is_string($aiAnalysis->structured_data)
                ? json_decode($aiAnalysis->structured_data, true)
                : $aiAnalysis->structured_data;
        }

        if (!$data) {
            return back()->with('error', 'Les données de structure sont invalides ou manquantes.');
        }

        // Résolution des références — évite les IDs hardcodés
        $defaultType     = DecisionType::first();
        $defaultCategory = DecisionCategory::first();

        if (!$defaultType || !$defaultCategory) {
            return back()->with('error', 'Aucun type ou catégorie de décision n\'est configuré. Veuillez d\'abord alimenter le référentiel (Administration → Types de décision).');
        }

        DB::beginTransaction();

        try {
            // 1. Décision — champs conformes au $fillable du modèle Decision
            $decision = Decision::create([
                'code'               => 'IA-' . strtoupper(Str::random(6)) . '-' . now()->format('Ymd'),
                'title'              => data_get($data, 'document_title') ?: ($aiAnalysis->document_title ?? 'Nouvelle Décision IA'),
                'official_reference' => data_get($data, 'document_reference') ?: $aiAnalysis->document_reference,
                'summary'            => data_get($data, 'summary'),
                'type_id'            => $defaultType->id,     // champ correct sur Decision
                'category_id'        => $defaultCategory->id, // champ correct sur Decision
                'status'             => 'draft',
                'priority'           => 3, // Moyenne (1=Critique, 2=Haute, 3=Moyenne, 4=Basse)
                'created_by'         => Auth::id(),
            ]);

            // 2. Plan d'action
            $actionPlan = ActionPlan::create([
                'decision_id'   => $decision->id,
                'title'         => 'Plan d\'action : ' . $decision->title,
                'status'        => 'draft',
                'progress_rate' => 0,
            ]);

            // 3. Axes stratégiques
            foreach ((array) data_get($data, 'axes_strategiques', []) as $axeData) {
                $axis = StrategicAxis::create([
                    'action_plan_id' => $actionPlan->id,
                    'title'          => data_get($axeData, 'title', 'Axe sans titre'),
                    'description'    => data_get($axeData, 'description'),
                ]);

                // 4. Actions métier
                foreach ((array) data_get($axeData, 'actions_metiers', []) as $actionData) {
                    $action = Action::create([
                        'strategic_axis_id' => $axis->id,
                        'title'             => data_get($actionData, 'title', 'Action sans titre'),
                        'description'       => data_get($actionData, 'description'),
                    ]);

                    // 5. Activités
                    foreach ((array) data_get($actionData, 'activites', []) as $activityData) {
                        $activity = Activity::create([
                            'action_id'   => $action->id,
                            'title'       => data_get($activityData, 'title', 'Activité sans titre'),
                            'description' => data_get($activityData, 'description'),
                        ]);

                        // 6. Livrables
                        foreach ((array) data_get($activityData, 'livrables', []) as $livData) {
                            Deliverable::create([
                                'activity_id' => $activity->id,
                                'title'       => data_get($livData, 'title', 'Livrable sans titre'),
                                'description' => data_get($livData, 'description'),
                            ]);
                        }

                        // 7. Jalons
                        foreach ((array) data_get($activityData, 'jalons', []) as $jalonData) {
                            Milestone::create([
                                'activity_id' => $activity->id,
                                'title'       => data_get($jalonData, 'title', 'Jalon sans titre'),
                                'description' => data_get($jalonData, 'description'),
                            ]);
                        }
                    }
                }
            }

            $aiAnalysis->update(['status' => 'finalized']);

            DB::commit();

            return redirect()
                ->route('decisions.show', $decision->id)
                ->with('success', 'Arborescence créée avec succès. La décision est en brouillon — complétez ses informations avant publication.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AiAnalysisController::confirm — erreur création arborescence', [
                'msg'   => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Erreur lors de la création de l\'arborescence : ' . $e->getMessage());
        }
    }
}
