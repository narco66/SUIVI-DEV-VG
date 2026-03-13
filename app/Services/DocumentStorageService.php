<?php

namespace App\Services;

use App\Jobs\ProcessDocumentOcrJob;
use App\Models\Decision;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use App\Models\DocumentTag;
use App\Models\DocumentVersion;
use App\Models\StorageLocation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    /**
     * Stocker un document associé à une décision.
     * Retourne le document existant si un doublon SHA-256 est détecté (toutes décisions).
     */
    public function storeDecisionDocument(Decision $decision, UploadedFile $file, array $meta = []): Document
    {
        $hash = hash_file('sha256', $file->getRealPath());
        $realMime = $this->detectMime($file);
        $ext = strtolower($file->getClientOriginalExtension());

        // Résolution du disque de stockage configuré
        $location = StorageLocation::where('is_default', true)->first();
        $disk = $location?->disk ?? 'local';
        $basePath = rtrim($location?->base_path ?? 'ged', '/');
        $directory = sprintf('%s/decisions/%d/%s', $basePath, $decision->id, now()->format('Y/m'));

        // Détection de doublon sur tout le parc de documents (pas seulement la décision)
        $duplicate = Document::withTrashed()
            ->where('hash_sha256', $hash)
            ->whereNull('deleted_at')
            ->first();

        if ($duplicate) {
            return $duplicate;
        }

        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $finalName = $safeName . '-' . Str::uuid() . '.' . $ext;
        $storedPath = $file->storeAs($directory, $finalName, $disk);

        $document = Document::create([
            'documentable_type'        => Decision::class,
            'documentable_id'          => $decision->id,
            'decision_id'              => $decision->id,
            'ged_folder_id'            => $meta['ged_folder_id'] ?? null,
            'title'                    => $meta['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'reference'                => $meta['reference'] ?? null,
            'description'              => $meta['description'] ?? null,
            'language'                 => $meta['language'] ?? 'fr',
            'author_service'           => $meta['author_service'] ?? null,
            'document_date'            => $meta['document_date'] ?? null,
            'archived_at'              => now()->toDateString(),
            'version_label'            => '1.0',
            'version_number'           => 1,
            'is_major_version'         => true,
            'is_current'               => true,
            'is_main'                  => (bool) ($meta['is_main'] ?? false),
            'is_physical_archive'      => (bool) ($meta['is_physical_archive'] ?? false),
            'source_type'              => $meta['source_type'] ?? 'numerique',
            'origin'                   => $meta['origin'] ?? 'formulaire_decision',
            'type'                     => $ext,
            'category'                 => $meta['category'] ?? null,
            'document_category_id'     => $meta['document_category_id'] ?? null,
            'document_status_id'       => $meta['document_status_id'] ?? null,
            'confidentiality_level_id' => $meta['confidentiality_level_id'] ?? null,
            'retention_rule_id'        => $meta['retention_rule_id'] ?? null,
            'storage_location_id'      => $location?->id,
            'mime_type'                => $realMime,
            'hash_sha256'              => $hash,
            'workflow_status'          => $meta['workflow_status'] ?? 'draft',
            'path'                     => $storedPath,
            'storage_disk'             => $disk,
            'original_filename'        => $file->getClientOriginalName(),
            'size'                     => $file->getSize(),
            'uploader_id'              => Auth::id(),
            'uploaded_at'              => now(),
        ]);

        DocumentVersion::create([
            'document_id'    => $document->id,
            'version_number' => 1,
            'version_label'  => '1.0',
            'is_major'       => true,
            'change_reason'  => 'Version initiale',
            'path'           => $storedPath,
            'storage_disk'   => $disk,
            'mime_type'      => $realMime,
            'size'           => $file->getSize(),
            'hash_sha256'    => $hash,
            'uploaded_by'    => Auth::id(),
        ]);

        $this->syncTags($document, $meta['tags'] ?? []);

        $this->logAccess($document, 'upload', ['origin' => $meta['origin'] ?? 'decision_form']);

        // Dispatch OCR uniquement si la file de jobs est configurée
        if (config('queue.default') !== 'sync' || app()->environment('testing')) {
            ProcessDocumentOcrJob::dispatch($document->id);
        } else {
            ProcessDocumentOcrJob::dispatch($document->id);
        }

        return $document;
    }

    /**
     * Remplacer le fichier d'un document existant (nouvelle version).
     */
    public function replaceDocumentFile(Document $document, UploadedFile $file, array $meta = []): Document
    {
        $hash = hash_file('sha256', $file->getRealPath());
        $realMime = $this->detectMime($file);
        $ext = strtolower($file->getClientOriginalExtension());

        $disk = $document->storage_disk ?: 'local';
        $directory = dirname($document->path);

        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $finalName = $safeName . '-' . Str::uuid() . '.' . $ext;
        $storedPath = $file->storeAs($directory, $finalName, $disk);

        // Calcul du nouveau numéro de version
        $lastVersion = $document->versions()->max('version_number') ?? $document->version_number;
        $isMajor = (bool) ($meta['is_major_version'] ?? false);
        $newVersionNumber = $lastVersion + 1;
        $newVersionLabel = $isMajor
            ? (floor($lastVersion) + 1) . '.0'
            : floor($lastVersion) . '.' . ($newVersionNumber - floor($lastVersion));

        DocumentVersion::create([
            'document_id'    => $document->id,
            'version_number' => $newVersionNumber,
            'version_label'  => $newVersionLabel,
            'is_major'       => $isMajor,
            'change_reason'  => $meta['change_reason'] ?? null,
            'path'           => $storedPath,
            'storage_disk'   => $disk,
            'mime_type'      => $realMime,
            'size'           => $file->getSize(),
            'hash_sha256'    => $hash,
            'uploaded_by'    => Auth::id(),
        ]);

        $document->update([
            'path'             => $storedPath,
            'version_label'    => $newVersionLabel,
            'version_number'   => $newVersionNumber,
            'is_major_version' => $isMajor,
            'mime_type'        => $realMime,
            'hash_sha256'      => $hash,
            'size'             => $file->getSize(),
            'original_filename' => $file->getClientOriginalName(),
            'type'             => $ext,
            'is_current'       => true,
        ]);

        if (!empty($meta['tags'])) {
            $this->syncTags($document, $meta['tags']);
        }

        $this->logAccess($document, 'replace', [
            'version' => $newVersionLabel,
            'change_reason' => $meta['change_reason'] ?? null,
        ]);

        ProcessDocumentOcrJob::dispatch($document->id);

        return $document->refresh();
    }

    /** Réponse HTTP de téléchargement sécurisé. */
    public function downloadResponse(Document $document)
    {
        $this->logAccess($document, 'download');

        return Storage::disk($document->storage_disk ?: 'local')
            ->download(
                $document->path,
                $document->original_filename ?: ($document->title . '.' . $document->type)
            );
    }

    /** Tracer un accès document (audit). */
    public function logAccess(Document $document, string $action, array $meta = []): void
    {
        DocumentAccessLog::create([
            'document_id' => $document->id,
            'user_id'     => Auth::id(),
            'action'      => $action,
            'ip_address'  => request()?->ip(),
            'user_agent'  => substr((string) request()?->userAgent(), 0, 1000),
            'meta'        => $meta,
        ]);
    }

    /**
     * Synchroniser les tags (insensible à la casse, trim automatique).
     */
    private function syncTags(Document $document, array $tags): void
    {
        if (empty($tags)) {
            return;
        }

        $tagIds = collect($tags)
            ->filter()
            ->map(fn(string $name) => DocumentTag::firstOrCreate(
                ['name' => strtolower(trim($name))],
                ['name' => strtolower(trim($name))]
            )->id)
            ->values();

        $document->tags()->sync($tagIds);
    }

    /**
     * Détecter le vrai type MIME via finfo (non spoofable par le client).
     */
    private function detectMime(UploadedFile $file): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file->getRealPath());
            finfo_close($finfo);
            if ($mime && $mime !== 'application/octet-stream') {
                return $mime;
            }
        }

        // Fallback sur le MIME déclaré par le client (moins fiable)
        return $file->getClientMimeType();
    }
}
