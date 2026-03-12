@php
    $documents = $documents ?? collect();
@endphp
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 0.05em;">
            <i class="bi bi-paperclip me-1"></i> Documents & Justificatifs
        </h6>
        <span class="badge bg-light text-dark">{{ $documents->count() }}</span>
    </div>

    <div class="card-body p-4">
        @if ($documents->count() > 0)
            <ul class="list-group list-group-flush mb-4">
                @foreach ($documents as $doc)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom-dashed">
                        <div class="d-flex align-items-center">
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
                                } else {
                                    $icon = 'bi-file-earmark text-secondary';
                                }
                            @endphp
                            <i class="bi {{ $icon }} fs-4 me-3"></i>
                            <div>
                                <a href="{{ route('documents.download', $doc->id) }}" class="text-dark fw-medium text-decoration-none d-block lh-sm" title="{{ $doc->title }}">
                                    {{ Str::limit($doc->title, 25) }}
                                </a>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ strtoupper((string) $doc->type) }} - {{ $doc->size }} KB - {{ optional($doc->uploaded_at)->format('d/m/Y') ?? '-' }}
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
                <p class="mt-2 mb-0 small">Aucun document rattache.</p>
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

                <div class="mb-2">
                    <label class="form-label small fw-medium mb-1">Titre du document *</label>
                    <input type="text" name="title" class="form-control form-control-sm" required placeholder="Ex: Lettre de transmission...">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium mb-1">Fichier * (Max 10MB)</label>
                    <input type="file" name="file" class="form-control form-control-sm" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .border-bottom-dashed { border-bottom: 1px dashed #dee2e6 !important; }
    .border-bottom-dashed:last-child { border-bottom: none !important; }
</style>
