<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentValidationActionRequest;
use App\Models\Document;
use App\Models\DocumentValidationFlow;
use App\Models\DocumentValidationStep;
use App\Models\DocumentWorkflow;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentValidationController extends Controller
{
    public function __construct(private readonly AuditTrailService $auditTrail)
    {
    }

    /** File des validations en attente pour l'utilisateur connecté. */
    public function index(Request $request)
    {
        abort_unless(
            auth()->user()?->can('documents.validate')
                || auth()->user()?->can('validate progress')
                || auth()->user()?->hasAnyRole([
                    'super_admin', 'admin_metier', 'validateur',
                    'point_focal', 'admin_technique',
                ]),
            403,
            'Vous n\'avez pas les droits pour accéder à la file de validation.'
        );

        $user  = Auth::user();
        $roles = $user?->roles->pluck('name')->toArray() ?? [];

        $pendingSteps = DocumentValidationStep::query()
            ->with(['flow.document.decision', 'flow.document.categoryRef', 'actor'])
            ->where('status', 'pending')
            ->where(function ($q) use ($user, $roles) {
                $q->whereIn('validator_role', $roles);
                if ($user) {
                    $q->orWhere('validator_user_id', $user->id);
                }
            })
            ->orderBy('created_at')
            ->paginate(20);

        return view('ged.validations.index', compact('pendingSteps'));
    }

    /** Lancer un workflow de validation multi-niveaux sur un document. */
    public function start(Document $document)
    {
        $this->authorize('startValidation', $document);

        $activeFlow = $document->validationFlows()
            ->where('status', 'in_review')
            ->latest()
            ->first();

        if ($activeFlow) {
            return back()->with('info', 'Un workflow de validation est déjà en cours pour ce document.');
        }

        if (!in_array($document->workflow_status, ['draft', 'rejected'], true)) {
            return back()->with('error', 'Ce document ne peut pas démarrer un workflow depuis son statut actuel.');
        }

        $flow = DocumentValidationFlow::create([
            'document_id'   => $document->id,
            'current_level' => 1,
            'final_level'   => 3,
            'status'        => 'in_review',
            'started_by'    => Auth::id(),
            'started_at'    => now(),
        ]);

        $roles = [
            1 => 'point_focal',
            2 => 'validateur',
            3 => 'admin_metier',
        ];

        foreach ($roles as $level => $role) {
            DocumentValidationStep::create([
                'flow_id'        => $flow->id,
                'level'          => $level,
                'validator_role' => $role,
                'status'         => $level === 1 ? 'pending' : 'waiting',
            ]);
        }

        $document->update(['workflow_status' => 'in_review']);

        $this->recordWorkflowChange($document, null, 'in_review', 'Workflow de validation lancé.');

        $this->auditTrail->log('document.validation.started', $document, null, [
            'flow_id' => $flow->id,
        ]);

        return back()->with('success', 'Workflow de validation lancé avec succès.');
    }

    /** Approuver l'étape courante. */
    public function approve(DocumentValidationActionRequest $request, DocumentValidationStep $step)
    {
        $this->assertCanActOnStep($step);

        if ($step->status !== 'pending') {
            return back()->with('error', 'Cette étape ne peut plus être traitée.');
        }

        $step->update([
            'status'   => 'approved',
            'acted_by' => Auth::id(),
            'acted_at' => now(),
            'comment'  => $request->validated('comment'),
        ]);

        $flow     = $step->flow;
        $nextStep = $flow->steps()->where('level', '>', $step->level)->orderBy('level')->first();

        if ($nextStep) {
            $nextStep->update(['status' => 'pending']);
            $flow->update(['current_level' => $nextStep->level]);
        } else {
            // Toutes les étapes approuvées → document validé
            $fromStatus = $flow->document->workflow_status;
            $flow->update([
                'status'       => 'approved',
                'completed_at' => now(),
                'current_level' => $flow->final_level,
            ]);
            $flow->document->update([
                'workflow_status' => 'validated',
                'validated_at'    => now(),
                'validated_by'    => Auth::id(),
            ]);
            $this->recordWorkflowChange($flow->document, $fromStatus, 'validated', $request->validated('comment'));
        }

        $this->auditTrail->log('document.validation.approved', $flow->document, null, [
            'step_id' => $step->id,
            'level'   => $step->level,
            'comment' => $request->validated('comment'),
        ]);

        return back()->with('success', 'Étape de validation approuvée.');
    }

    /** Rejeter l'étape courante — renvoie le document en brouillon. */
    public function reject(DocumentValidationActionRequest $request, DocumentValidationStep $step)
    {
        $this->assertCanActOnStep($step);

        if ($step->status !== 'pending') {
            return back()->with('error', 'Cette étape ne peut plus être traitée.');
        }

        $step->update([
            'status'   => 'rejected',
            'acted_by' => Auth::id(),
            'acted_at' => now(),
            'comment'  => $request->validated('comment'),
        ]);

        $flow       = $step->flow;
        $fromStatus = $flow->document->workflow_status;

        $flow->update([
            'status'       => 'rejected',
            'completed_at' => now(),
        ]);

        // Remet le document en brouillon pour permettre la resoumission
        $flow->document->update(['workflow_status' => 'rejected']);

        $this->recordWorkflowChange(
            $flow->document,
            $fromStatus,
            'rejected',
            $request->validated('comment')
        );

        $this->auditTrail->log('document.validation.rejected', $flow->document, null, [
            'step_id' => $step->id,
            'level'   => $step->level,
            'comment' => $request->validated('comment'),
        ]);

        return back()->with('success', 'Étape rejetée. Le document est retourné à l\'état "Rejeté".');
    }

    /** Vérifier que l'utilisateur courant peut agir sur cette étape. */
    private function assertCanActOnStep(DocumentValidationStep $step): void
    {
        $user  = Auth::user();
        $roles = $user?->roles->pluck('name')->toArray() ?? [];

        $canAct = ($step->validator_user_id && $step->validator_user_id === $user?->id)
            || ($step->validator_role && in_array($step->validator_role, $roles, true));

        abort_unless(
            $canAct || $user?->can('documents.validate') || $user?->hasAnyRole(['super_admin', 'admin_metier']),
            403,
            'Vous n\'êtes pas autorisé à traiter cette étape de validation.'
        );
    }

    /** Enregistrer une transition de statut workflow dans l'historique. */
    private function recordWorkflowChange(Document $document, ?string $from, string $to, ?string $comment): void
    {
        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_status' => $from,
            'to_status'   => $to,
            'comment'     => $comment,
            'changed_by'  => Auth::id(),
        ]);
    }
}
