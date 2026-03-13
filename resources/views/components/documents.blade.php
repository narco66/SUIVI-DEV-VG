@php
    $documents = $documents ?? collect();
@endphp
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 0.05em;">
            <i class="bi bi-paperclip me-1"></i> GED et Archives
        </h6>
        <span class="badge bg-light text-dark">{{ $documents->count() }}</span>
    </div>

    <div class="card-body p-4">
        @if ($documents->count() > 0)
            <ul class="list-group list-group-flush mb-4">
                @foreach ($documents as $doc)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-start border-bottom-dashed">
                        <div class="d-flex align-items-start">
                            @php
                                $type = strtolower((string) ($doc->type ?? ''));
                                if ($type === 'pdf') {
                                    $icon = 'bi-file-pdf text-danger';
                                } elseif (in_array($type, ['doc', 'docx'], true)) {
                                    $icon = 'bi-file-word text-primary';
                                } elseif (in_array($type, ['xls', 'xlsx'], true)) {
                                    $icon = 'bi-file-excel text-success';
                                } elseif (in_array($type, ['jpg', 'jpeg', 'png'], true)) {
                                    $icon = 'bi-file-image text-info';
                                } elseif (in_array($type, ['zip'], true)) {
                                    $icon = 'bi-file-zip text-warning';
                                } else {
                                    $icon = 'bi-file-earmark text-secondary';
                                }
                            @endphp
                            <i class="bi {{ $icon }} fs-4 me-3"></i>
                            <div>
                                <a href="{{ route('documents.download', $doc->id) }}" class="text-dark fw-medium text-decoration-none d-block lh-sm" title="{{ $doc->title }}">
                                    {{ $doc->title }}
                                </a>
                                <small class="text-muted d-block">
                                    {{ strtoupper((string) $doc->type) }} • {{ number_format(((int) $doc->size) / 1024, 2) }} MB • {{ optional($doc->uploaded_at)->format('d/m/Y') ?? '-' }}
                                </small>
                                <small class="text-muted d-block">
                                    Réf: {{ $doc->reference ?: '-' }} • Version: {{ $doc->version_label ?: '1.0' }} • Statut: {{ $doc->workflow_status ?: 'draft' }}
                                </small>
                                <small class="text-muted d-block">
                                    Catégorie: {{ $doc->category ?: ($doc->categoryRef->name ?? '-') }} • Confidentialité: {{ $doc->confidentialityLevel->name ?? '-' }}
                                </small>
                            </div>
                        </div>

                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce document ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center text-muted mb-4">
                <i class="bi bi-inboxes text-light" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 small">Aucun document rattaché.</p>
            </div>
        @endif

        <button class="btn btn-outline-primary w-100 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#uploadForm" aria-expanded="false" aria-controls="uploadForm">
            <i class="bi bi-cloud-upload me-2"></i> Ajouter un document
        </button>

        <div class="collapse mt-3" id="uploadForm">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-3 rounded border">
                @csrf
                <input type="hidden" name="documentable_type" value="{{ $modelClass }}">
                <input type="hidden" name="documentable_id" value="{{ $modelId }}">

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-medium mb-1">Titre du document *</label>
                        <input type="text" name="title" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium mb-1">Référence documentaire</label>
                        <input type="text" name="reference" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium mb-1">Catégorie</label>
                        <input type="text" name="category" class="form-control form-control-sm" placeholder="acte_signe, annexe...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium mb-1">Langue</label>
                        <input type="text" name="language" class="form-control form-control-sm" value="fr">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium mb-1">Description</label>
                        <textarea name="description" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium mb-1">Fichier * (Max 50MB)</label>
                        <input type="file" name="file" class="form-control form-control-sm" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-sm btn-primary px-3">Téléverser</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .border-bottom-dashed:last-child { border-bottom: none !important; }
</style>