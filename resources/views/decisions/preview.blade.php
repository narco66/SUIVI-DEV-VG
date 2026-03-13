@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h4 class="fw-bold mb-0">Prévisualisation de la décision</h4>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Titre officiel</dt>
                <dd class="col-md-9">{{ $decision->title ?? '-' }}</dd>
                <dt class="col-md-3">Type d'acte</dt>
                <dd class="col-md-9">{{ $decision->act_type ?? '-' }}</dd>
                <dt class="col-md-3">Référence</dt>
                <dd class="col-md-9">{{ $decision->official_reference ?? '-' }}</dd>
                <dt class="col-md-3">Date d'adoption</dt>
                <dd class="col-md-9">{{ $decision->date_adoption ?? '-' }}</dd>
                <dt class="col-md-3">Résumé</dt>
                <dd class="col-md-9">{{ $decision->summary ?? '-' }}</dd>
                <dt class="col-md-3">Dispositions</dt>
                <dd class="col-md-9">{{ $decision->main_provisions ?? '-' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection

