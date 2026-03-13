@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-grid-1x2-fill fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Pilotage</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Tableau de Bord Exécutif</h2>
                        <p class="text-muted mb-2">Aperçu stratégique de la mise en œuvre des décisions (CEEAC).</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Vision consolidée</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-stretch">
            <div class="h-100 w-100 d-flex align-items-center gap-2 rounded-4 border bg-white p-4 shadow-sm">
                <button class="btn btn-outline-secondary px-4">
                    <i class="bi bi-download me-2"></i> Exporter
                </button>
                <a href="{{ route('decisions.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-plus-lg me-2"></i> Nouvelle Décision
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3 fade-in" style="animation-delay: 0.1s;">
            <div class="card h-100 border-0 shadow-sm bg-primary bg-opacity-10 rounded-4 hover-elevate overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3 text-primary">
                        <i class="bi bi-pie-chart-fill" style="font-size: 5rem;"></i>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle icon-lg me-3">
                            <i class="bi bi-pie-chart-fill fs-4"></i>
                        </div>
                        <div>
                            <p class="text-uppercase fw-semibold text-primary mb-0" style="font-size: 0.75rem; letter-spacing: 0.05em;">Taux d'exécution global</p>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark">{{ number_format($globalExecutionRate, 1) }}%</h2>
                    <div class="d-flex align-items-center text-primary small fw-medium mt-2 pb-1">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        <span>Moyenne sur toutes les décisions</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3 fade-in" style="animation-delay: 0.2s;">
            <div class="card h-100 border-0 shadow-sm bg-warning bg-opacity-10 rounded-4 hover-elevate overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3 text-warning">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 5rem;"></i>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle icon-lg me-3">
                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        </div>
                        <div>
                            <p class="text-uppercase fw-semibold text-warning mb-0" style="font-size: 0.75rem; letter-spacing: 0.05em;">Décisions en retard</p>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark">{{ $delayedDecisions }}</h2>
                    <div class="d-flex align-items-center {{ $highPriorityDelayed > 0 ? 'text-danger' : 'text-warning' }} small fw-medium mt-2 pb-1">
                        <i class="bi bi-arrow-up-right me-1"></i>
                        <span>Priorité haute : {{ $highPriorityDelayed }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3 fade-in" style="animation-delay: 0.3s;">
            <div class="card h-100 border-0 shadow-sm bg-info bg-opacity-10 rounded-4 hover-elevate overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3 text-info">
                        <i class="bi bi-check2-circle text-info" style="font-size: 5rem;"></i>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle icon-lg me-3">
                            <i class="bi bi-check2-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-uppercase fw-semibold text-info mb-0" style="font-size: 0.75rem; letter-spacing: 0.05em;">Attentes de validation</p>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark">{{ $pendingValidations }}</h2>
                    <div class="d-flex align-items-center text-info small fw-medium mt-2 pb-1">
                        <i class="bi bi-dash me-1 text-info"></i>
                        <span>Rapports d'avancement soumis</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3 fade-in" style="animation-delay: 0.4s;">
            <div class="card h-100 border-0 shadow-sm bg-success bg-opacity-10 rounded-4 hover-elevate overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3">
                        <i class="bi bi-file-earmark-medical-fill" style="font-size: 5rem;"></i>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-white text-success rounded-circle icon-lg me-3">
                            <i class="bi bi-file-earmark-medical-fill fs-4"></i>
                        </div>
                        <div>
                            <p class="text-uppercase fw-bold text-success mb-0" style="font-size: 0.75rem; letter-spacing: 0.05em;">Couverture Documentaire</p>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark">82%</h2>
                    <div class="d-flex align-items-center text-success small fw-medium mt-2 pb-1">
                        <i class="bi bi-arrow-up-right me-1"></i>
                        <span>Objectif positif (>80%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8 fade-in" style="animation-delay: 0.5s;">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center border-bottom-0">
                    <div>
                        <h5 class="card-title fw-bold mb-1 text-dark">Performance globale</h5>
                        <p class="small text-muted mb-0">Taux d'exécution moyen par domaine d'intervention</p>
                    </div>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <canvas id="performanceChart" style="min-height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 fade-in" style="animation-delay: 0.6s;">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center border-bottom border-light">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="bi bi-activity text-primary me-2"></i> Activités Récentes</h5>
                    <a href="{{ route('progress-updates.index') }}" class="btn btn-sm btn-light border text-primary">Tout voir</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                            <div class="list-group-item p-3 border-light list-group-item-action">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <h6 class="mb-0 fw-semibold text-truncate text-dark" style="max-width: 70%;">
                                        {{ $activity->updatable->title ?? $activity->updatable->code ?? 'Rapport' }}
                                    </h6>
                                    <span class="badge {{ $activity->status == 'pending' ? 'bg-warning-subtle text-warning' : ($activity->status == 'validated' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger') }} rounded-pill align-self-start">
                                        {{ $activity->status == 'pending' ? 'Examen' : ($activity->status == 'validated' ? 'Validé' : 'Rejeté') }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="small text-muted text-truncate" style="max-width: 60%;">
                                        <i class="bi bi-person me-1"></i> {{ $activity->author->name ?? 'Système' }} a soumis <strong>{{ $activity->progress_rate }}%</strong>
                                    </div>
                                    <small class="text-muted text-nowrap"><i class="bi bi-clock me-1"></i> {{ $activity->submitted_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-muted">
                                <i class="bi bi-inbox fs-2 text-light mb-3 d-block"></i>
                                <span class="small">Aucune activité récente.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const chartLabels = {!! json_encode($chartLabels) !!};
    const chartData = {!! json_encode($chartData) !!};

    if (chartLabels.length === 0) {
        chartLabels.push('Aucune donnée disponible');
        chartData.push(0);
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: "Taux d'exécution moyen (%)",
                data: chartData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endsection