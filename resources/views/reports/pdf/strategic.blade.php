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
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0056b3;
            font-size: 20pt;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 10pt;
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
        
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            font-size: 8pt;
            color: #999;
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rapport Stratégique de Suivi</h1>
        <p>Généré le: {{ date('d/m/Y à H:i') }}</p>
    </div>

    <!-- KPIs globaux -->
    <table class="kpi-container pb-0">
        <tr>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value">{{ $totalDecisions }}</p>
                    <p class="kpi-label">Décisions Suivies</p>
                </div>
            </td>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value" style="color: #198754;">{{ number_format($avgProgress, 1) }}%</p>
                    <p class="kpi-label">Avancement Moyen</p>
                </div>
            </td>
            <td style="width: 25%">
                <div class="kpi-box">
                    <p class="kpi-value" style="color: #ffc107;">{{ $delayedDecisions }}</p>
                    <p class="kpi-label">En Souffrance</p>
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

    <h2 class="section-title">Points d'Attention Prioritaires</h2>
    @if($priorityDecisions->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%">Numéro / Type</th>
                    <th style="width: 45%">Intitulé de la Décision</th>
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

    <h2 class="section-title">Analyse par Domaine Stratégique</h2>
    
    @foreach($domains as $domain)
        @php
            $domainDecisionsCount = $domain->decisions->count();
            $domainAvgProgress = $domainDecisionsCount > 0
                ? $domain->decisions->avg('progress_rate')
                : 0;
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
                        <th style="width: 25%">Statut Global</th>
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
                                    <span style="color: #dc3545; font-weight: bold;">En Retard</span>
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
