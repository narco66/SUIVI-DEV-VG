<?php

namespace App\Http\Controllers;

use App\Models\ConfidentialityLevel;
use App\Models\Decision;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GedController extends Controller
{
    public function __construct(private readonly DocumentStorageService $storageService)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Document::class);

        $query = Document::query()
            ->with(['decision', 'uploader', 'confidentialityLevel', 'categoryRef', 'tags'])
            ->withoutTrashed();

        // --- Recherche plein texte ---
        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $driver = config('database.default');
            $connection = config("database.connections.$driver.driver");

            if ($connection === 'mysql') {
                $query->whereRaw(
                    "MATCH(title, reference, description) AGAINST (? IN BOOLEAN MODE)",
                    [$search . '*']
                );
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('reference', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }
        }

        // --- Filtres structurés ---
        if ($request->filled('document_category_id')) {
            $query->where('document_category_id', (int) $request->document_category_id);
        }

        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }

        if ($request->filled('decision_id')) {
            $query->where('decision_id', (int) $request->decision_id);
        }

        if ($request->filled('type')) {
            $query->where('type', strtolower($request->type));
        }

        if ($request->filled('confidentiality_level_id')) {
            $query->where('confidentiality_level_id', (int) $request->confidentiality_level_id);
        }

        if ($request->filled('author_service')) {
            $query->where('author_service', 'like', '%' . $request->author_service . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('document_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('document_date', '<=', $request->date_to);
        }

        if ($request->filled('is_physical_archive')) {
            $query->where('is_physical_archive', (bool) $request->is_physical_archive);
        }

        // --- Tri ---
        $sortField = in_array($request->sort, ['title', 'document_date', 'uploaded_at', 'size', 'type'])
            ? $request->sort
            : 'uploaded_at';
        $sortDir = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $documents = $query->paginate(20)->withQueryString();

        // --- Stats (sans soft-deleted) ---
        $stats = [
            'total'        => Document::withoutTrashed()->count(),
            'published'    => Document::withoutTrashed()->where('workflow_status', 'published')->count(),
            'archived'     => Document::withoutTrashed()->where('workflow_status', 'archived')->count(),
            'confidential' => Document::withoutTrashed()->whereNotNull('confidentiality_level_id')->count(),
            'in_review'    => Document::withoutTrashed()->where('workflow_status', 'in_review')->count(),
        ];

        // --- Listes pour filtres ---
        $categories          = DocumentCategory::where('is_active', true)->orderBy('name')->get();
        $confidentialityLevels = ConfidentialityLevel::orderBy('rank')->get();
        $workflowStatuses    = [
            'draft'     => 'Brouillon',
            'in_review' => 'En revue',
            'validated' => 'Validé',
            'rejected'  => 'Rejeté',
            'published' => 'Publié',
            'archived'  => 'Archivé',
            'obsolete'  => 'Obsolète',
        ];
        $fileTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];

        return view('ged.index', compact(
            'documents',
            'stats',
            'categories',
            'confidentialityLevels',
            'workflowStatuses',
            'fileTypes'
        ));
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $document->load([
            'decision',
            'versions.uploader',
            'accessLogs' => fn($q) => $q->latest()->limit(30),
            'accessLogs.user',
            'uploader',
            'tags',
            'validationFlows.steps.validator',
            'categoryRef',
            'confidentialityLevel',
            'retentionRule',
            'storageLocation',
            'archiveReferences',
            'workflows.changer',
        ]);

        // Tracer la consultation
        app(DocumentStorageService::class)->logAccess($document, 'view');

        return view('ged.show', compact('document'));
    }

    /** Export CSV des documents filtrés. */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Document::class);

        $query = Document::query()
            ->with(['decision', 'uploader', 'confidentialityLevel', 'categoryRef'])
            ->withoutTrashed();

        // Mêmes filtres que l'index
        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('document_category_id')) {
            $query->where('document_category_id', (int) $request->document_category_id);
        }
        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }
        if ($request->filled('type')) {
            $query->where('type', strtolower($request->type));
        }

        $documents = $query->orderBy('uploaded_at', 'desc')->limit(5000)->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ged-export-' . now()->format('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($documents) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Titre', 'Référence', 'Catégorie', 'Type', 'Version',
                'Statut workflow', 'Confidentialité', 'Décision', 'Déposant',
                'Date document', 'Date dépôt', 'Taille (Ko)',
            ], ';');

            foreach ($documents as $doc) {
                fputcsv($handle, [
                    $doc->id,
                    $doc->title,
                    $doc->reference,
                    $doc->categoryRef?->name,
                    strtoupper($doc->type),
                    $doc->version_label,
                    $doc->workflowStatusLabel(),
                    $doc->confidentialityLevel?->name,
                    $doc->decision?->code,
                    $doc->uploader?->name,
                    $doc->document_date?->format('d/m/Y'),
                    $doc->uploaded_at?->format('d/m/Y H:i'),
                    $doc->size ? number_format($doc->size / 1024, 1) : '',
                ], ';');
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
