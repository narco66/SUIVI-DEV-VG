<?php

namespace App\Http\Controllers;

use App\Exports\DecisionExport;
use App\Http\Requests\DecisionAutosaveRequest;
use App\Http\Requests\DecisionRequest;
use App\Models\ConfidentialityLevel;
use App\Models\Country;
use App\Models\Decision;
use App\Models\DecisionCategory;
use App\Models\DecisionType;
use App\Models\Department;
use App\Models\Direction;
use App\Models\DocumentCategory;
use App\Models\DocumentStatus;
use App\Models\Domain;
use App\Models\Institution;
use App\Models\Session;
use App\Models\User;
use App\Services\AuditTrailService;
use App\Services\DocumentStorageService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DecisionController extends Controller
{
    public function __construct(
        private readonly DocumentStorageService $documentStorage,
        private readonly AuditTrailService $auditTrail,
    ) {
    }

    public function index(Request $request)
    {
        $query = Decision::with(['type', 'category', 'domain', 'session', 'institution']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('official_reference', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $decisions = $query->latest()->paginate(10)->withQueryString();

        return view('decisions.index', compact('decisions'));
    }

    public function create()
    {
        return view('decisions.create', $this->creationFormData());
    }

    public function autosave(DecisionAutosaveRequest $request)
    {
        $validated = $request->validated();
        $decisionId = $validated['decision_id'] ?? null;

        if ($decisionId) {
            $decision = Decision::findOrFail($decisionId);
            $this->authorize('update', $decision);
            $decision->update([
                'title' => $validated['title'] ?? $decision->title,
                'type_id' => $validated['type_id'] ?? $decision->type_id,
                'priority' => $validated['priority'] ?? $decision->priority ?? 3,
                'status' => 'draft',
                'date_adoption' => $validated['date_adoption'] ?? $decision->date_adoption ?? now()->toDateString(),
                'last_autosaved_at' => now(),
            ]);
        } else {
            $this->authorize('create', Decision::class);

            $decision = Decision::create([
                'title' => $validated['title'] ?? 'Brouillon sans titre',
                'type_id' => $validated['type_id'] ?? DecisionType::query()->value('id'),
                'priority' => $validated['priority'] ?? 3,
                'status' => 'draft',
                'date_adoption' => $validated['date_adoption'] ?? now()->toDateString(),
                'created_by' => auth()->id() ?? 1,
                'code' => 'DRAFT-' . date('Ymd-His') . '-' . strtoupper(substr(uniqid(), -4)),
                'last_autosaved_at' => now(),
            ]);
        }

        return response()->json([
            'ok' => true,
            'decision_id' => $decision->id,
            'last_autosaved_at' => optional($decision->last_autosaved_at)->toDateTimeString(),
        ]);
    }

    public function preview(DecisionRequest $request)
    {
        $payload = $request->validated();

        return view('decisions.preview', [
            'decision' => (object) $payload,
            'formData' => $this->creationFormData(),
        ]);
    }

    public function store(DecisionRequest $request)
    {
        $data = $this->normalizeDecisionPayload($request);
        $draftId = (int) $request->input('decision_id');
        $decision = null;

        if ($draftId > 0) {
            $existingDraft = Decision::query()
                ->where('id', $draftId)
                ->where('status', 'draft')
                ->where('created_by', auth()->id())
                ->first();

            if ($existingDraft) {
                $existingDraft->update($data);
                $decision = $existingDraft->fresh();
            }
        }

        if (!$decision) {
            $decision = Decision::create($data);
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $meta = $request->input('document_meta.' . $index, []);
                $this->documentStorage->storeDecisionDocument($decision, $file, $meta);
            }
        }

        $this->auditTrail->log('decision.created', $decision, null, $decision->toArray());

        $message = $decision->status === 'draft'
            ? 'Décision enregistrée en brouillon.'
            : 'Décision enregistrée et soumise avec succès.';

        return redirect()->route('decisions.show', $decision)->with('success', $message);
    }

    public function show(Decision $decision)
    {
        $decision->load([
            'type',
            'category',
            'domain',
            'session',
            'institution',
            'creator',
            'decisionDocuments.tags',
            'decisionDocuments.versions',
            'documents',
            'actorAssignments.user',
            'actorAssignments.institution',
            'actionPlans',
        ]);

        $users = User::all();

        return view('decisions.show', compact('decision', 'users'));
    }

    public function edit(Decision $decision)
    {
        return view('decisions.edit', array_merge(
            ['decision' => $decision],
            $this->creationFormData(),
        ));
    }

    public function update(DecisionRequest $request, Decision $decision)
    {
        $old = $decision->toArray();
        $data = $this->normalizeDecisionPayload($request, false);
        $decision->update($data);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $meta = $request->input('document_meta.' . $index, []);
                $this->documentStorage->storeDecisionDocument($decision, $file, $meta);
            }
        }

        $this->auditTrail->log('decision.updated', $decision, $old, $decision->fresh()->toArray());

        return redirect()->route('decisions.show', $decision)
            ->with('success', 'Décision mise à jour avec succès.');
    }

    public function destroy(Decision $decision)
    {
        $old = $decision->toArray();
        $decision->delete();

        $this->auditTrail->log('decision.deleted', $decision, $old, null);

        return redirect()->route('decisions.index')
            ->with('success', 'Décision supprimée avec succès.');
    }

    public function export(Request $request)
    {
        return Excel::download(new DecisionExport($request->search, $request->status), 'decisions_' . date('Y_m_d') . '.xlsx');
    }

    private function creationFormData(): array
    {
        return [
            'sessions' => Session::all(),
            'types' => DecisionType::all(),
            'categories' => DecisionCategory::all(),
            'domains' => Domain::all(),
            'institutions' => Institution::all(),
            'departments' => Department::orderBy('name')->get(),
            'directions' => Direction::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
            'documentCategories' => DocumentCategory::orderBy('name')->get(),
            'documentStatuses' => DocumentStatus::orderBy('id')->get(),
            'confidentialityLevels' => ConfidentialityLevel::orderBy('rank')->get(),
        ];
    }

    private function normalizeDecisionPayload(DecisionRequest $request, bool $isCreate = true): array
    {
        $data = $request->validated();

        $status = $request->input('form_action') === 'draft' ? 'draft' : ($data['status'] ?? 'active');

        $data['status'] = $status;
        $data['created_by'] = $isCreate ? (auth()->id() ?? 1) : ($request->route('decision')->created_by ?? auth()->id() ?? 1);
        $data['code'] = $data['code'] ?? 'DEC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        $data['submitted_at'] = $status === 'draft' ? null : now();
        $data['last_autosaved_at'] = now();
        $data['tags'] = collect($data['tags'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : null)
            ->filter()
            ->values()
            ->all();
        $data['available_languages'] = collect($data['available_languages'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : null)
            ->filter()
            ->values()
            ->all();
        $data['co_responsible_user_ids'] = $data['co_responsible_user_ids'] ?? [];
        $data['member_states'] = $data['member_states'] ?? [];
        $data['stakeholders'] = $data['stakeholders'] ?? [];

        unset($data['form_action'], $data['documents'], $data['document_meta']);

        return $data;
    }
}
