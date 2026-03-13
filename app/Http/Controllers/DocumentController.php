<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Decision;
use App\Models\Document;
use App\Models\DocumentMetadataHistory;
use App\Services\AuditTrailService;
use App\Services\DocumentStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentStorageService $storageService,
        private readonly AuditTrailService $auditTrail,
    ) {
    }

    /** Déposer un nouveau document. */
    public function store(DocumentRequest $request)
    {
        $this->authorize('create', Document::class);

        $documentableType = ltrim($request->documentable_type, '\\');
        $documentableId   = (int) $request->documentable_id;

        // Actuellement seul Decision est supporté ; extensible via un registry
        $supportedTypes = [Decision::class];
        if (!in_array($documentableType, $supportedTypes, true)) {
            return back()->withErrors(['documentable_type' => 'Type d\'objet non pris en charge pour le moment.']);
        }

        $parent = Decision::findOrFail($documentableId);

        $meta         = $request->validated();
        $meta['tags'] = $request->input('tags', []);

        $document = $this->storageService->storeDecisionDocument($parent, $request->file('file'), $meta);

        $this->auditTrail->log('document.uploaded', $document, null, $document->toArray());

        return $this->redirectAfterAction($request, $document, 'Document ajouté avec succès.');
    }

    /** Remplacer le fichier ou mettre à jour les métadonnées d'un document (nouvelle version). */
    public function update(DocumentRequest $request, Document $document)
    {
        $this->authorize('update', $document);

        $old = $document->toArray();

        if ($request->hasFile('file')) {
            // Nouvelle version du fichier
            $meta         = $request->validated();
            $meta['tags'] = $request->input('tags', []);
            $document     = $this->storageService->replaceDocumentFile($document, $request->file('file'), $meta);
        } else {
            // Mise à jour des métadonnées uniquement
            $data = collect($request->validated())
                ->except(['file', 'documentable_type', 'documentable_id'])
                ->toArray();

            DocumentMetadataHistory::create([
                'document_id' => $document->id,
                'changed_by'  => auth()->id(),
                'old_values'  => $old,
                'new_values'  => $data,
            ]);

            $document->update($data);

            if ($request->filled('tags')) {
                $document->tags()->sync(
                    collect($request->input('tags', []))
                        ->filter()
                        ->map(fn($name) => \App\Models\DocumentTag::firstOrCreate(
                            ['name' => strtolower(trim($name))]
                        )->id)
                        ->values()
                );
            }
        }

        $this->auditTrail->log('document.updated', $document, $old, $document->fresh()?->toArray());

        return $this->redirectAfterAction($request, $document, 'Document mis à jour avec succès.');
    }

    /** Supprimer un document (suppression logique). */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $old = $document->toArray();
        $document->delete();

        $this->auditTrail->log('document.deleted', $document, $old, null);

        // Redirection vers la décision parente si possible
        if ($document->decision_id) {
            return redirect()
                ->route('decisions.show', $document->decision_id)
                ->with('success', 'Document supprimé avec succès.');
        }

        return redirect()
            ->route('ged.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    /** Télécharger un fichier document de manière sécurisée. */
    public function download(Document $document)
    {
        $this->authorize('download', $document);

        $disk = $document->storage_disk ?: 'local';

        if (!Storage::disk($disk)->exists($document->path)) {
            return redirect()->back()->withErrors(['Fichier introuvable sur le serveur.']);
        }

        return $this->storageService->downloadResponse($document);
    }

    /**
     * Rediriger après une action sur un document.
     * Priorité : GED show > Décision show > back().
     */
    private function redirectAfterAction(Request $request, Document $document, string $message)
    {
        // Si on vient de la GED, retourner sur la fiche GED
        if ($request->has('_redirect_ged')) {
            return redirect()
                ->route('ged.show', $document)
                ->with('success', $message);
        }

        // Sinon, retourner sur la décision parente
        if ($document->decision_id) {
            return redirect()
                ->route('decisions.show', $document->decision_id)
                ->with('success', $message);
        }

        return redirect()->back()->with('success', $message);
    }
}
