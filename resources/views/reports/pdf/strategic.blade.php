<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Stratégique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #e9f2ff;
            border: 1px solid #cfe2ff;
            border-left: 6px solid #0d6efd;
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 24px;
            text-align: left;
        }
        .header-kicker {
            margin: 0 0 6px 0;
            color: #0d6efd;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .header h1 {
            color: #0b3d91;
            font-size: 20pt;
            margin: 0 0 4px 0;
        }
        .header-subtitle {
            margin: 0;
            color: #5b6675;
            font-size: 10pt;
        }
        .meta-row {
            margin-top: 10px;
        }
        .meta-badge {
            display: inline-block;
            background-color: #fff;
            border: 1px solid #b8daff;
            color: #0d6efd;
            border-radius: 14px;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 4px 10px;
            margin-right: 8px;
        }
        .section-title {
            color: #154c79;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 14pt;
        }
        .kpi-container {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: separate;
            border-spacing: 15px 0;
        }
        .kpi-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            text-align: center;
            padding: 15px 10px;
        }
        .kpi-value {
            font-size: 24pt;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
            line-height: 1.2;
        }
        .kpi-label {
            font-size: 9pt;
            color: #6c757d;
            text-transform: uppercase;
            margin: 5px 0 0 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #495057;
            text-align: left;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            color: white;
        }
        .bg-success { background-color: #198754; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
        .bg-primary { background-color: #0d6efd; }
        .bg-secondary { background-color: #6c757d; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <p class="header-kicker">Pilotage stratégique</p>
        <h1>Rapport stratégique de suivi</h1>
        <p class="header-subtitle">Synthèse exécutive de la performance institutionnelle</p>
        <div class="meta-row">
            <span class="meta-badge">Décisions : {{ $totalDecisions }}</span>
            <span class="meta-badge">Généré le : {{ date('d/m/Y H:i') }}</span>
        </div>
    </div>

    <table class="kpi-container">
        <tr>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value">{{ $totalDecisions }}</p>
                    <p class="kpi-label">Décisions suivies</p>
                </div>
            </td>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value" style="color: #198754;">{{ number_format($avgProgress, 1) }}%</p>
                    <p class="kpi-label">Avancement moyen</p>
                </div>
            </td>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value" style="color: #ffc107;">{{ $delayedDecisions }}</p>
                    <p class="kpi-label">En souffrance</p>
                </div>
            </td>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value" style="color: #0d6efd;">{{ $completedDecisions }}</p>
                    <p class="kpi-label">Achevées</p>
                </div>
            </td>
        </tr>
    </table>

    <h2 class="section-title">Points d'attention prioritaires</h2>
    @if($priorityDecisions->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%">Numéro / Type</th>
                    <th style="width: 45%">Intitulé de la décision</th>
                    <th style="width: 15%">Statut</th>
                    <th style="width: 25%">Institution / Échéance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($priorityDecisions as $pc)
                <tr>
                    <td>
                        <strong>{{ $pc->reference ?? 'N/A' }}</strong><br>
                        <span style="font-size: 8pt; color:#666;">{{ $pc->type->name ?? '' }}</span>
                    </td>
                    <td>{{ $pc->title }}</td>
                    <td>
                        @if($pc->status == 'completed')
                            <span class="badge bg-success">Achevé</span>
                        @elseif($pc->status == 'active')
                            <span class="badge bg-primary">En cours</span>
                        @elseif($pc->status == 'delayed')
                            <span class="badge bg-danger">En retard</span>
                        @else
                            <span class="badge bg-secondary">{{ $pc->status }}</span>
                        @endif
                        @if($pc->priority == 1)
                            <span class="badge bg-warning" style="margin-top: 5px;">Priorité ++</span>
                        @endif
                    </td>
                    <td>
                        {{ $pc->institution->name ?? 'N/A' }}<br>
                        @if($pc->deadline)
                            <strong style="color: {{ $pc->deadline->isPast() ? 'red' : 'inherit' }}">{{ $pc->deadline->format('d/m/Y') }}</strong>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 10pt; color: #666; font-style: italic;">Aucune décision prioritaire ou en retard signalée pour le moment.</p>
    @endif

    <div class="page-break"></div>

    <h2 class="section-title">Analyse par domaine stratégique</h2>
    @foreach($domains as $domain)
        @php
            $domainDecisionsCount = $domain->decisions->count();
            $domainAvgProgress = $domainDecisionsCount > 0 ? $domain->decisions->avg('progress_rate') : 0;
        @endphp

        <div style="background-color: #f1f5f9; padding: 10px; margin-bottom: 10px; border-left: 4px solid #0d6efd;">
            <strong style="font-size: 11pt;">{{ $domain->name }}</strong>
            <span style="float: right; color: #666; font-size: 10pt;">
                (Décisions: {{ $domainDecisionsCount }} | Perf: <strong>{{ number_format($domainAvgProgress, 1) }}%</strong>)
            </span>
        </div>

        @if($domainDecisionsCount > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50%">Décision</th>
                        <th style="width: 25%">Avancement</th>
                        <th style="width: 25%">Statut global</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domain->decisions as $dec)
                        @php $decAvg = $dec->progress_rate ?? 0; @endphp
                        <tr>
                            <td>{{ $dec->title }}</td>
                            <td>
                                <div style="background-color: #e9ecef; width: 100%; height: 10px; border-radius: 5px; margin-top: 5px; overflow: hidden;">
                                    <div style="width: {{ $decAvg }}%; height: 100%; background-color: {{ $decAvg == 100 ? '#198754' : '#0d6efd' }};"></div>
                                </div>
                                <div style="text-align: center; font-size: 8pt; margin-top: 2px;">{{ number_format($decAvg, 1) }}%</div>
                            </td>
                            <td>
                                @if($dec->status == 'completed')
                                    <span style="color: #198754; font-weight: bold;">Achevé</span>
                                @elseif($dec->status == 'delayed')
                                    <span style="color: #dc3545; font-weight: bold;">En retard</span>
                                @else
                                    <span style="color: #0d6efd;">Normal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>
</html>
