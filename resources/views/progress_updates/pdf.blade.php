<!DOCTYPE html>
<html>
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
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0056b3;
            margin-bottom: 5px;
            font-size: 24px;
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
        <h1>SUIVI-DEC</h1>
        <h2>Fiche de Rapport d'Avancement #{{ $update->id }}</h2>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
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
        Fiche générée automatiquement par le système SUIVI-DEC. <br>
        © {{ date('Y') }} CEEAC
    </div>
</body>
</html>
