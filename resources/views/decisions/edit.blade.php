@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Décisions</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Modifier la Décision</h2>
                        <p class="text-muted mb-2">{{ $decision->code }} - {{ \Illuminate\Support\Str::limit($decision->title, 60) }}</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Édition complète</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-stretch">
            <div class="h-100 w-100 d-flex align-items-center justify-content-center rounded-4 border bg-white p-4 shadow-sm">
                <a href="{{ route('decisions.show', $decision) }}" class="btn btn-outline-secondary px-4">
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

    <form method="POST" action="{{ route('decisions.update', $decision) }}" enctype="multipart/form-data" id="decision-edit-form">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 pb-0">
                <ul class="nav nav-tabs" id="decisionEditTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-identite" type="button">Identification</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-contenu" type="button">Contenu métier</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pilotage" type="button">Pilotage</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ged" type="button">Archives & GED</button></li>
                </ul>
            </div>

            <div class="card-body p-4 tab-content">
                <div class="tab-pane fade show active" id="tab-identite" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type d'acte *</label>
                            <select name="act_type" class="form-select">
                                @php $actType = old('act_type', $decision->act_type ?: 'decision'); @endphp
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
                            <input type="text" name="official_reference" class="form-control" value="{{ old('official_reference', $decision->official_reference) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code interne</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $decision->code) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Intitulé officiel *</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title', $decision->title) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Titre court</label>
                            <input type="text" name="short_title" class="form-control" value="{{ old('short_title', $decision->short_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de décision (référentiel) *</label>
                            <select name="type_id" class="form-select" required>
                                <option value="">Sélectionner</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id', $decision->type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Session / mandature / édition</label>
                            <select name="session_id" class="form-select">
                                <option value="">Sans session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('session_id', $decision->session_id) == $session->id ? 'selected' : '' }}>{{ $session->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'adoption *</label>
                            <input type="date" name="date_adoption" class="form-control" required value="{{ old('date_adoption', optional($decision->date_adoption)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de signature</label>
                            <input type="date" name="date_signature" class="form-control" value="{{ old('date_signature', optional($decision->date_signature)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'entrée en vigueur</label>
                            <input type="date" name="date_effective" class="form-control" value="{{ old('date_effective', optional($decision->date_effective)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Domaine</label>
                            <select name="domain_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" {{ old('domain_id', $decision->domain_id) == $domain->id ? 'selected' : '' }}>{{ $domain->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sous-domaine</label>
                            <input type="text" name="sub_domain" class="form-control" value="{{ old('sub_domain', $decision->sub_domain) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Portée géographique</label>
                            <input type="text" name="geographic_scope" class="form-control" value="{{ old('geographic_scope', $decision->geographic_scope) }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-contenu" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Résumé exécutif</label><textarea name="summary" rows="3" class="form-control">{{ old('summary', $decision->summary) }}</textarea></div>
                        <div class="col-12"><label class="form-label">Objet de la décision</label><textarea name="object" rows="2" class="form-control">{{ old('object', $decision->object) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Contexte</label><textarea name="context" rows="3" class="form-control">{{ old('context', $decision->context) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Justification</label><textarea name="justification" rows="3" class="form-control">{{ old('justification', $decision->justification) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Base légale / textes de référence</label><textarea name="legal_basis" rows="3" class="form-control">{{ old('legal_basis', $decision->legal_basis) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Considérants</label><textarea name="considerations" rows="3" class="form-control">{{ old('considerations', $decision->considerations) }}</textarea></div>
                        <div class="col-12"><label class="form-label">Dispositions principales</label><textarea name="main_provisions" rows="4" class="form-control">{{ old('main_provisions', $decision->main_provisions) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Articles / points clés</label><textarea name="key_articles" rows="3" class="form-control">{{ old('key_articles', $decision->key_articles) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Résultats attendus</label><textarea name="expected_results" rows="3" class="form-control">{{ old('expected_results', $decision->expected_results) }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Langue source</label><input type="text" name="source_language" class="form-control" value="{{ old('source_language', $decision->source_language) }}"></div>
                        <div class="col-md-6"><label class="form-label">Langues disponibles (séparées par virgule)</label><input type="text" name="available_languages[]" class="form-control" value="{{ old('available_languages.0', is_array($decision->available_languages) ? implode(', ', $decision->available_languages) : '') }}"></div>
                        <div class="col-12"><label class="form-label">Mots-clés (séparés par virgule)</label><input type="text" name="tags[]" class="form-control" value="{{ old('tags.0', is_array($decision->tags) ? implode(', ', $decision->tags) : '') }}"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-pilotage" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Département pilote</label><select name="pilot_department_id" class="form-select"><option value="">Sélectionner</option>@foreach($departments as $department)<option value="{{ $department->id }}" {{ old('pilot_department_id', $decision->pilot_department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Direction responsable</label><select name="responsible_direction_id" class="form-select"><option value="">Sélectionner</option>@foreach($directions as $direction)<option value="{{ $direction->id }}" {{ old('responsible_direction_id', $decision->responsible_direction_id) == $direction->id ? 'selected' : '' }}>{{ $direction->name }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Responsable principal</label><select name="primary_owner_id" class="form-select"><option value="">Sélectionner</option>@foreach($users as $user)<option value="{{ $user->id }}" {{ old('primary_owner_id', $decision->primary_owner_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach</select></div>

                        <div class="col-md-3"><label class="form-label">Priorité *</label><select name="priority" class="form-select" required><option value="1" {{ old('priority', $decision->priority) == 1 ? 'selected' : '' }}>Critique</option><option value="2" {{ old('priority', $decision->priority) == 2 ? 'selected' : '' }}>Haute</option><option value="3" {{ old('priority', $decision->priority) == 3 ? 'selected' : '' }}>Moyenne</option><option value="4" {{ old('priority', $decision->priority) == 4 ? 'selected' : '' }}>Basse</option></select></div>
                        <div class="col-md-3"><label class="form-label">Statut</label><select name="status" class="form-select"><option value="draft" {{ old('status', $decision->status) === 'draft' ? 'selected' : '' }}>Brouillon</option><option value="active" {{ old('status', $decision->status) === 'active' ? 'selected' : '' }}>Actif</option><option value="in_progress" {{ old('status', $decision->status) === 'in_progress' ? 'selected' : '' }}>En exécution</option><option value="delayed" {{ old('status', $decision->status) === 'delayed' ? 'selected' : '' }}>En retard</option><option value="completed" {{ old('status', $decision->status) === 'completed' ? 'selected' : '' }}>Achevée</option></select></div>
                        <div class="col-md-3"><label class="form-label">Échéance globale</label><input type="date" name="global_deadline" class="form-control" value="{{ old('global_deadline', optional($decision->global_deadline)->format('Y-m-d')) }}"></div>
                        <div class="col-md-3"><label class="form-label">Mode de suivi</label><input type="text" name="monitoring_mode" class="form-control" value="{{ old('monitoring_mode', $decision->monitoring_mode) }}"></div>

                        <div class="col-md-3 form-check mt-4 ms-2"><input type="checkbox" class="form-check-input" name="requires_action_plan" value="1" {{ old('requires_action_plan', $decision->requires_action_plan) ? 'checked' : '' }}><label class="form-check-label">Nécessite plan d'action</label></div>
                        <div class="col-md-3 form-check mt-4"><input type="checkbox" class="form-check-input" name="requires_indicators" value="1" {{ old('requires_indicators', $decision->requires_indicators) ? 'checked' : '' }}><label class="form-check-label">Nécessite indicateurs</label></div>
                        <div class="col-md-3 form-check mt-4"><input type="checkbox" class="form-check-input" name="requires_deliverables" value="1" {{ old('requires_deliverables', $decision->requires_deliverables) ? 'checked' : '' }}><label class="form-check-label">Nécessite livrables</label></div>
                        <div class="col-md-3 form-check mt-4"><input type="checkbox" class="form-check-input" name="requires_budget" value="1" {{ old('requires_budget', $decision->requires_budget) ? 'checked' : '' }}><label class="form-check-label">Nécessite budget</label></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-ged" role="tabpanel">
                    <div class="alert alert-info border-0">Ajoutez de nouveaux documents ou consultez ceux déjà rattachés à la décision.</div>
                    @include('components.documents', [
                        'documents' => $decision->decisionDocuments,
                        'modelClass' => \App\Models\Decision::class,
                        'modelId' => $decision->id,
                    ])
                </div>
            </div>

            <div class="card-footer bg-white border-0 d-flex flex-wrap gap-2 justify-content-end pt-0 pb-4 px-4">
                <a href="{{ route('decisions.show', $decision) }}" class="btn btn-light border px-4">Annuler</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2-circle me-2"></i>Mettre à jour</button>
            </div>
        </div>
    </form>
</div>
@endsection