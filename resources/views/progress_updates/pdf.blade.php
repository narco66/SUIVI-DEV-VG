<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport d'avancement #{{ $update->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            background-color: #e9f2ff;
            border: 1px solid #cfe2ff;
            border-left: 6px solid #0d6efd;
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 20px;
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
            margin: 0 0 4px 0;
            font-size: 20pt;
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
        .info-block {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 150px;
        }
        h3 {
            color: #0056b3;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-top: 25px;
            font-size: 18px;
        }
        p {
            margin-top: 5px;
            margin-bottom: 15px;
            text-align: justify;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-primary { background-color: #cce5ff; color: #004085; border: 1px solid #b8daff; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .validations {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 13px;
        }
        .validations th, .validations td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .validations th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="header-kicker">Suivi opérationnel</p>
        <h1>Fiche de rapport d'avancement #{{ $update->id }}</h1>
        <p class="header-subtitle">Synthèse détaillée du rapport soumis</p>
        <div class="meta-row">
            <span class="meta-badge">Taux: {{ $update->progress_rate }}%</span>
            <span class="meta-badge">Généré le: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Élément concerné :</span>
            {{ class_basename($update->updatable_type) }} - {{ $update->updatable->title ?? $update->updatable->code ?? 'N/A' }}
        </div>
        <div class="info-row">
            <span class="info-label">Auteur du rapport :</span>
            {{ $update->author->name ?? 'Système' }} ({{ $update->author->email ?? 'N/A' }})
        </div>
        <div class="info-row">
            <span class="info-label">Date de soumission :</span>
            {{ $update->submitted_at ? $update->submitted_at->format('d/m/Y H:i') : '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Statut global :</span>
            @if($update->status === 'validated')
                <span class="badge badge-success">Validé</span>
            @elseif($update->status === 'rejected')
                <span class="badge badge-danger">Rejeté</span>
            @else
                <span class="badge badge-warning">En attente</span>
            @endif
        </div>
        <div class="info-row" style="margin-top: 10px;">
            <span class="info-label">Taux déclaré :</span>
            <span class="badge badge-primary" style="font-size: 14px;">{{ $update->progress_rate }}%</span>
        </div>
    </div>

    <h3>1. Réalisations</h3>
    <p>{{ $update->achievements ?: 'Aucune réalisation renseignée.' }}</p>

    <h3>2. Difficultés rencontrées</h3>
    <p>{{ $update->difficulties ?: 'Aucune difficulté renseignée.' }}</p>

    <h3>3. Prochaines étapes</h3>
    <p>{{ $update->next_steps ?: 'Aucune étape renseignée.' }}</p>

    <h3>4. Besoins d'appui</h3>
    <p>{{ $update->support_needs ?: 'Aucun besoin renseigné.' }}</p>

    @if($update->validations->count() > 0)
    <h3>5. Historique des validations</h3>
    <table class="validations">
        <thead>
            <tr>
                <th>Niveau</th>
                <th>Validateur</th>
                <th>Date</th>
                <th>Décision</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($update->validations as $validation)
            <tr>
                <td>{{ $validation->level }}</td>
                <td>{{ $validation->validator->name ?? 'Inconnu' }}</td>
                <td>{{ $validation->validated_at ? $validation->validated_at->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    <span class="badge {{ $validation->status === 'approved' ? 'badge-success' : 'badge-danger' }}">
                        {{ $validation->status === 'approved' ? 'Approuvé' : 'Rejeté' }}
                    </span>
                </td>
                <td>{{ $validation->comment ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Fiche générée automatiquement par le système SUIVI-DEC.<br>
        © {{ date('Y') }} CEEAC
    </div>
</body>
</html>
