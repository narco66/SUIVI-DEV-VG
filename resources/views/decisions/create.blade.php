@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-journal-plus fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Décisions</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Nouvelle Décision Institutionnelle</h2>
                        <p class="text-muted mb-2">Saisie complète de l'acte et archivage GED des sources.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Formulaire multi-sections</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-stretch">
            <div class="h-100 w-100 d-flex align-items-center justify-content-center rounded-4 border bg-white p-4 shadow-sm">
                <a href="{{ route('decisions.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <h6 class="fw-bold mb-2">Le formulaire contient des erreurs :</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('decisions.store') }}" enctype="multipart/form-data" id="decision-form">
        @csrf
        <input type="hidden" name="decision_id" id="decision_id" value="{{ old('decision_id') }}">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 pb-0">
                <ul class="nav nav-tabs" id="decisionTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-identite" type="button">Identification</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-contenu" type="button">Contenu métier</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pilotage" type="button">Pilotage</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ged" type="button">Archives & GED</button></li>
                </ul>
            </div>

            <div class="card-body p-4 tab-content" id="decisionTabContent">
                <div class="tab-pane fade show active" id="tab-identite" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type d'acte *</label>
                            <select name="act_type" class="form-select">
                                @php $actType = old('act_type', 'decision'); @endphp
                                <option value="decision" {{ $actType === 'decision' ? 'selected' : '' }}>Décision</option>
                                <option value="reglement" {{ $actType === 'reglement' ? 'selected' : '' }}>Règlement</option>
                                <option value="directive" {{ $actType === 'directive' ? 'selected' : '' }}>Directive</option>
                                <option value="resolution" {{ $actType === 'resolution' ? 'selected' : '' }}>Résolution</option>
                                <option value="rapport" {{ $actType === 'rapport' ? 'selected' : '' }}>Rapport</option>
                                <option value="compte_rendu" {{ $actType === 'compte_rendu' ? 'selected' : '' }}>Compte rendu</option>
                                <option value="communique_final" {{ $actType === 'communique_final' ? 'selected' : '' }}>Communiqué final</option>
                                <option value="recommandation" {{ $actType === 'recommandation' ? 'selected' : '' }}>Recommandation</option>
                                <option value="autre" {{ $actType === 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Numéro / référence officielle</label>
                            <input type="text" name="official_reference" class="form-control" value="{{ old('official_reference') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code interne</label>
                            <input type="text" name="code" class="form-control" placeholder="Auto si vide" value="{{ old('code') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Intitulé officiel *</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Titre court</label>
                            <input type="text" name="short_title" class="form-control" value="{{ old('short_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de décision (référentiel) *</label>
                            <select name="type_id" class="form-select" required>
                                <option value="">Sélectionner</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Session / mandature / édition</label>
                            <select name="session_id" class="form-select">
                                <option value="">Sans session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>{{ $session->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'adoption *</label>
                            <input type="date" name="date_adoption" class="form-control" required value="{{ old('date_adoption') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de signature</label>
                            <input type="date" name="date_signature" class="form-control" value="{{ old('date_signature') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'entrée en vigueur</label>
                            <input type="date" name="date_effective" class="form-control" value="{{ old('date_effective') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Domaine</label>
                            <select name="domain_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>{{ $domain->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sous-domaine</label>
                            <input type="text" name="sub_domain" class="form-control" value="{{ old('sub_domain') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Portée géographique</label>
                            <input type="text" name="geographic_scope" class="form-control" value="{{ old('geographic_scope', 'CEEAC') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-contenu" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Résumé exécutif</label>
                            <textarea name="summary" rows="3" class="form-control">{{ old('summary') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Objet de la décision</label>
                            <textarea name="object" rows="2" class="form-control">{{ old('object') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contexte</label>
                            <textarea name="context" rows="3" class="form-control">{{ old('context') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Justification</label>
                            <textarea name="justification" rows="3" class="form-control">{{ old('justification') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Base légale / textes de référence</label>
                            <textarea name="legal_basis" rows="3" class="form-control">{{ old('legal_basis') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Considérants</label>
                            <textarea name="considerations" rows="3" class="form-control">{{ old('considerations') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dispositions principales</label>
                            <textarea name="main_provisions" rows="4" class="form-control">{{ old('main_provisions') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Articles / points clés</label>
                            <textarea name="key_articles" rows="3" class="form-control">{{ old('key_articles') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Résultats attendus</label>
                            <textarea name="expected_results" rows="3" class="form-control">{{ old('expected_results') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Langue source</label>
                            <input type="text" name="source_language" class="form-control" placeholder="fr" value="{{ old('source_language', 'fr') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Langues disponibles (séparées par virgule)</label>
                            <input type="text" name="available_languages[]" class="form-control" placeholder="fr, en" value="{{ old('available_languages.0') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mots-clés (séparés par virgule)</label>
                            <input type="text" name="tags[]" class="form-control" placeholder="gouvernance, sécurité, finances" value="{{ old('tags.0') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-pilotage" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Département pilote</label>
                            <select name="pilot_department_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('pilot_department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Direction responsable</label>
                            <select name="responsible_direction_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($directions as $direction)
                                    <option value="{{ $direction->id }}" {{ old('responsible_direction_id') == $direction->id ? 'selected' : '' }}>{{ $direction->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Responsable principal</label>
                            <select name="primary_owner_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('primary_owner_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Priorité *</label>
                            <select name="priority" class="form-select" required>
                                <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Critique</option>
                                <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Haute</option>
                                <option value="3" {{ old('priority', 3) == 3 ? 'selected' : '' }}>Moyenne</option>
                                <option value="4" {{ old('priority') == 4 ? 'selected' : '' }}>Basse</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut initial</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>En exécution</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Échéance globale</label>
                            <input type="date" name="global_deadline" class="form-control" value="{{ old('global_deadline') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mode de suivi</label>
                            <input type="text" name="monitoring_mode" class="form-control" value="{{ old('monitoring_mode', 'mensuel') }}">
                        </div>
                        <div class="col-md-3 form-check mt-4 ms-2">
                            <input type="checkbox" class="form-check-input" name="requires_action_plan" value="1" {{ old('requires_action_plan', 1) ? 'checked' : '' }}>
                            <label class="form-check-label">Nécessite plan d'action</label>
                        </div>
                        <div class="col-md-3 form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="requires_indicators" value="1" {{ old('requires_indicators') ? 'checked' : '' }}>
                            <label class="form-check-label">Nécessite indicateurs</label>
                        </div>
                        <div class="col-md-3 form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="requires_deliverables" value="1" {{ old('requires_deliverables') ? 'checked' : '' }}>
                            <label class="form-check-label">Nécessite livrables</label>
                        </div>
                        <div class="col-md-3 form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="requires_budget" value="1" {{ old('requires_budget') ? 'checked' : '' }}>
                            <label class="form-check-label">Nécessite budget</label>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-ged" role="tabpanel">
                    <div class="alert alert-info border-0">
                        Déposez les archives physiques numérisées et documents sources. Formats autorisés: PDF, DOCX, XLSX, PPTX, JPG, PNG, ZIP.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fichiers documentaires</label>
                        <input type="file" name="documents[]" id="documentsInput" class="form-control" multiple>
                        <div class="form-text">Glisser-déposer possible dans les navigateurs compatibles.</div>
                    </div>

                    <div id="documentMetaContainer" class="row g-3"></div>
                </div>
            </div>

            <div class="card-footer bg-white border-0 d-flex flex-wrap gap-2 justify-content-end pt-0 pb-4 px-4">
                <div class="me-auto align-self-center small text-muted" id="autosaveStatus">Autosave inactif</div>
                <button type="submit" name="form_action" value="draft" class="btn btn-light border px-4">
                    <i class="bi bi-save me-2"></i>Enregistrer en brouillon
                </button>
                <button type="submit" formaction="{{ route('decisions.preview') }}" class="btn btn-outline-primary px-4">
                    <i class="bi bi-eye me-2"></i>Prévisualiser
                </button>
                <button type="submit" name="form_action" value="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2-circle me-2"></i>Soumettre
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('documentsInput');
    const container = document.getElementById('documentMetaContainer');
    const form = document.getElementById('decision-form');
    const autosaveStatus = document.getElementById('autosaveStatus');
    const decisionIdInput = document.getElementById('decision_id');

    input.addEventListener('change', function () {
        container.innerHTML = '';
        Array.from(input.files).forEach((file, index) => {
            const baseName = file.name.replace(/\.[^/.]+$/, '');
            const html = `
                <div class="col-12">
                    <div class="border rounded-3 p-3 bg-light bg-opacity-50">
                        <h6 class="fw-bold mb-3">${file.name}</h6>
                        <div class="row g-2">
                            <div class="col-md-4"><input class="form-control form-control-sm" name="document_meta[${index}][title]" value="${baseName}" placeholder="Titre document"></div>
                            <div class="col-md-4">
                                <select class="form-select form-select-sm" name="document_meta[${index}][category]">
                                    <option value="acte_signe">Acte signé</option>
                                    <option value="projet_acte">Projet d'acte</option>
                                    <option value="version_travail">Version de travail</option>
                                    <option value="rapport_session">Rapport de session</option>
                                    <option value="compte_rendu">Compte rendu</option>
                                    <option value="piece_justificative">Pièce justificative</option>
                                    <option value="annexe">Annexe</option>
                                    <option value="archive_historique">Archive historique</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="document_meta[${index}][reference]" placeholder="Référence"></div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="document_meta[${index}][language]" placeholder="Langue (fr)"></div>
                            <div class="col-md-4"><input class="form-control form-control-sm" name="document_meta[${index}][author_service]" placeholder="Service émetteur"></div>
                            <div class="col-md-4"><input type="date" class="form-control form-control-sm" name="document_meta[${index}][document_date]"></div>
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });
    });

    async function autosaveDecision() {
        const formData = new FormData(form);
        const payload = {
            decision_id: decisionIdInput.value || null,
            title: formData.get('title') || null,
            type_id: formData.get('type_id') || null,
            priority: formData.get('priority') || 3,
            status: 'draft',
            date_adoption: formData.get('date_adoption') || null,
        };

        try {
            const response = await fetch('{{ route('decisions.autosave') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                },
                body: JSON.stringify(payload),
            });
            if (!response.ok) {
                throw new Error('autosave_failed');
            }
            const data = await response.json();
            if (data.decision_id) {
                decisionIdInput.value = data.decision_id;
            }
            autosaveStatus.textContent = 'Brouillon auto-enregistré : ' + (data.last_autosaved_at || 'OK');
        } catch (e) {
            autosaveStatus.textContent = 'Échec autosave';
        }
    }

    setInterval(autosaveDecision, 30000);
});
</script>
@endsection
