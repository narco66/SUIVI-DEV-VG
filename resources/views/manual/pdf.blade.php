<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manuel Utilisateur — SUIVI-DEC CEEAC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.55;
            color: #2d3748;
        }

        /* ── Cover page ── */
        .cover {
            page-break-after: always;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 40px;
            background: #0b3d91;
        }
        .cover-logo {
            font-size: 48pt;
            color: #fff;
            margin-bottom: 20px;
        }
        .cover-subtitle {
            font-size: 11pt;
            color: #90cdf4;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .cover h1 {
            font-size: 30pt;
            color: #ffffff;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .cover-tagline {
            font-size: 13pt;
            color: #bee3f8;
            margin-bottom: 40px;
        }
        .cover-divider {
            width: 80px;
            height: 4px;
            background: #63b3ed;
            margin: 0 auto 40px;
            border-radius: 2px;
        }
        .cover-meta {
            font-size: 9pt;
            color: #90cdf4;
        }
        .cover-meta strong {
            color: #fff;
        }

        /* ── TOC page ── */
        .toc-page {
            page-break-after: always;
            padding: 40px;
        }
        .toc-page h2 {
            font-size: 18pt;
            color: #0b3d91;
            margin-bottom: 24px;
            border-bottom: 3px solid #0b3d91;
            padding-bottom: 8px;
        }
        .toc-entry {
            display: flex;
            align-items: baseline;
            margin-bottom: 8px;
        }
        .toc-entry .toc-num {
            font-weight: bold;
            color: #0d6efd;
            min-width: 30px;
        }
        .toc-entry .toc-title {
            flex: 1;
            color: #2d3748;
        }
        .toc-entry .toc-dots {
            border-bottom: 1px dotted #a0aec0;
            flex: 1;
            margin: 0 6px 3px;
        }
        .toc-entry .toc-page-num {
            color: #718096;
            font-size: 9pt;
        }
        .toc-sub {
            padding-left: 20px;
            font-size: 9.5pt;
            color: #4a5568;
            margin-bottom: 5px;
        }

        /* ── Section styles ── */
        .section {
            page-break-before: always;
            padding: 40px;
        }
        .section-header {
            background: linear-gradient(135deg, #0b3d91, #1565c0);
            color: white;
            padding: 16px 20px;
            border-radius: 6px;
            margin-bottom: 24px;
        }
        .section-header .section-num {
            font-size: 9pt;
            opacity: 0.75;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .section-header h2 {
            font-size: 17pt;
            font-weight: bold;
        }

        /* Subsections */
        h3 {
            font-size: 13pt;
            color: #0b3d91;
            margin-top: 22px;
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
        }
        h4 {
            font-size: 11pt;
            color: #1565c0;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        p {
            margin-bottom: 8px;
        }
        ul, ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        li {
            margin-bottom: 4px;
        }

        /* Info boxes */
        .box {
            border-radius: 5px;
            padding: 12px 16px;
            margin: 12px 0;
        }
        .box-info {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
            color: #2c5282;
        }
        .box-tip {
            background: #f0fff4;
            border-left: 4px solid #38a169;
            color: #276749;
        }
        .box-warning {
            background: #fffbeb;
            border-left: 4px solid #d69e2e;
            color: #744210;
        }
        .box-danger {
            background: #fff5f5;
            border-left: 4px solid #e53e3e;
            color: #742a2a;
        }
        .box-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 9.5pt;
        }
        thead tr {
            background: #0b3d91;
            color: white;
        }
        th {
            padding: 9px 12px;
            text-align: left;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        /* Steps */
        .steps {
            counter-reset: step;
            margin: 10px 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .step-num {
            background: #0b3d91;
            color: white;
            border-radius: 50%;
            min-width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 9pt;
            margin-right: 10px;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .step-body { flex: 1; }
        .step-body strong { color: #0b3d91; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-gray   { background: #f3f4f6; color: #374151; }
        .badge-purple { background: #ede9fe; color: #5b21b6; }

        /* Role pill */
        .role-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 8.5pt;
            font-weight: bold;
            background: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
            margin: 2px;
        }

        /* Inline code */
        code {
            background: #f1f5f9;
            padding: 1px 5px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
            font-size: 9pt;
            color: #d53f8c;
        }

        /* Page footer */
        @page { margin: 15mm 15mm 18mm 15mm; }
        .page-footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }

        /* Intro page  */
        .intro { padding: 40px; }
        .intro p { font-size: 11pt; line-height: 1.7; }
        .module-grid {
            margin: 20px 0;
        }
        .module-card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
            padding: 10px 14px;
            margin-bottom: 8px;
        }
        .module-card strong { color: #0b3d91; }

        /* Workflow diagram (text-based) */
        .workflow {
            margin: 14px 0;
        }
        .wf-step {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 5px 12px;
            font-size: 9pt;
            font-weight: bold;
            margin: 3px;
        }
        .wf-arrow {
            color: #0b3d91;
            font-weight: bold;
            margin: 0 2px;
        }

        /* Inline icon placeholder */
        .icon { font-weight: bold; }

        hr.divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 16px 0;
        }
    </style>
</head>
<body>

<!-- Footer on every page -->
<div class="page-footer">
    Manuel Utilisateur SUIVI-DEC — Commission de la CEEAC &nbsp;|&nbsp; Version 1.0 — {{ date('d/m/Y') }} &nbsp;|&nbsp; Confidentiel
</div>

<!-- ══════════════════════════════════════════════
     PAGE DE COUVERTURE
     ══════════════════════════════════════════════ -->
<div class="cover">
    <div class="cover-logo">&#127758;</div>
    <p class="cover-subtitle">Commission Économique des États de l'Afrique Centrale</p>
    <h1>SUIVI-DEC</h1>
    <p class="cover-tagline">Système de Suivi-Évaluation des Décisions Institutionnelles</p>
    <div class="cover-divider"></div>
    <p class="cover-meta">
        <strong>Manuel Utilisateur</strong><br>
        Version 1.0 &mdash; {{ date('d F Y') }}<br><br>
        Document réservé aux agents habilités de la Commission de la CEEAC
    </p>
</div>


<!-- ══════════════════════════════════════════════
     TABLE DES MATIÈRES
     ══════════════════════════════════════════════ -->
<div class="toc-page">
    <h2>Table des Matières</h2>

    <div class="toc-entry">
        <span class="toc-num">1.</span>
        <span class="toc-title">Introduction &amp; Vue d'ensemble</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">3</span>
    </div>
    <div class="toc-sub">1.1 Présentation de la plateforme &nbsp;·&nbsp; 1.2 Rôles et accès &nbsp;·&nbsp; 1.3 Navigation</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">2.</span>
        <span class="toc-title">Authentification</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">4</span>
    </div>
    <div class="toc-sub">2.1 Connexion &nbsp;·&nbsp; 2.2 Réinitialisation du mot de passe</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">3.</span>
        <span class="toc-title">Tableau de Bord</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">5</span>
    </div>
    <div class="toc-sub">3.1 Indicateurs clés &nbsp;·&nbsp; 3.2 Graphiques de suivi</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">4.</span>
        <span class="toc-title">Gestion des Décisions</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">6</span>
    </div>
    <div class="toc-sub">4.1 Créer une décision &nbsp;·&nbsp; 4.2 Consulter et modifier &nbsp;·&nbsp; 4.3 Export CSV &nbsp;·&nbsp; 4.4 Statuts et priorités</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">5.</span>
        <span class="toc-title">Plans d'Action &amp; Axes Stratégiques</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">8</span>
    </div>
    <div class="toc-sub">5.1 Plans d'action &nbsp;·&nbsp; 5.2 Axes stratégiques &nbsp;·&nbsp; 5.3 Actions &amp; Activités</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">6.</span>
        <span class="toc-title">Suivi de la Mise en Œuvre</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">10</span>
    </div>
    <div class="toc-sub">6.1 Mises à jour de progrès &nbsp;·&nbsp; 6.2 Jalons &amp; Livrables &nbsp;·&nbsp; 6.3 Indicateurs</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">7.</span>
        <span class="toc-title">Circuit de Validation</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">11</span>
    </div>
    <div class="toc-sub">7.1 Workflow d'approbation &nbsp;·&nbsp; 7.2 Actions de validation &nbsp;·&nbsp; 7.3 Cas de rejet</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">8.</span>
        <span class="toc-title">GED — Gestion Électronique des Documents</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">12</span>
    </div>
    <div class="toc-sub">8.1 Dépôt de documents &nbsp;·&nbsp; 8.2 Recherche &amp; Filtres &nbsp;·&nbsp; 8.3 Versioning &nbsp;·&nbsp; 8.4 Validation documentaire &nbsp;·&nbsp; 8.5 Export</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">9.</span>
        <span class="toc-title">Assistant IA — Analyse de Décisions</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">14</span>
    </div>
    <div class="toc-sub">9.1 Lancer une analyse &nbsp;·&nbsp; 9.2 Interpréter les résultats &nbsp;·&nbsp; 9.3 Importer dans la base &nbsp;·&nbsp; 9.4 Limites et avertissements</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">10.</span>
        <span class="toc-title">Rapports Stratégiques</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">16</span>
    </div>
    <div class="toc-sub">10.1 Rapport global PDF &nbsp;·&nbsp; 10.2 Rapport de progression PDF</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">11.</span>
        <span class="toc-title">Administration &amp; Paramétrage</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">17</span>
    </div>
    <div class="toc-sub">11.1 Types &amp; Catégories &nbsp;·&nbsp; 11.2 Organisation institutionnelle &nbsp;·&nbsp; 11.3 Utilisateurs &amp; Rôles &nbsp;·&nbsp; 11.4 Journal d'audit</div>

    <div class="toc-entry" style="margin-top:10px;">
        <span class="toc-num">12.</span>
        <span class="toc-title">Glossaire &amp; Questions Fréquentes</span>
        <span class="toc-dots"></span>
        <span class="toc-page-num">19</span>
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 1 — INTRODUCTION
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 1</div>
        <h2>Introduction &amp; Vue d'ensemble</h2>
    </div>

    <h3>1.1 Présentation de la plateforme</h3>
    <p>
        <strong>SUIVI-DEC</strong> est la plateforme numérique officielle de la <strong>Commission de la CEEAC</strong>
        (Communauté Économique des États de l'Afrique Centrale) pour le suivi-évaluation de la mise en œuvre
        des décisions institutionnelles. Elle centralise la planification stratégique, le suivi des activités,
        la gestion documentaire et l'exploitation de l'intelligence artificielle.
    </p>

    <div class="module-grid">
        <div class="module-card"><strong>Décisions</strong> — Enregistrement et suivi des actes officiels (décisions, résolutions, recommandations).</div>
        <div class="module-card"><strong>Plans d'action</strong> — Décomposition en axes stratégiques, actions métiers et activités opérationnelles.</div>
        <div class="module-card"><strong>Suivi d'avancement</strong> — Mises à jour de progrès, jalons, livrables et indicateurs de performance.</div>
        <div class="module-card"><strong>Validation</strong> — Circuit d'approbation multi-niveaux (Point Focal → Validateur → Admin Métier).</div>
        <div class="module-card"><strong>GED</strong> — Gestion Électronique des Documents avec versioning, contrôle d'accès et workflow.</div>
        <div class="module-card"><strong>Assistant IA</strong> — Analyse automatique des actes institutionnels par GPT-4o pour structurer les plans d'action.</div>
        <div class="module-card"><strong>Rapports</strong> — Génération de rapports stratégiques PDF consolidés.</div>
        <div class="module-card"><strong>Administration</strong> — Paramétrage institutionnel, gestion des utilisateurs, journal d'audit.</div>
    </div>

    <h3>1.2 Rôles et accès</h3>
    <table>
        <thead>
            <tr>
                <th>Rôle</th>
                <th>Accès principaux</th>
                <th>Restrictions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="role-pill">Administrateur</span></td>
                <td>Accès complet à tous les modules, paramétrage, gestion des utilisateurs, journal d'audit.</td>
                <td>Aucune</td>
            </tr>
            <tr>
                <td><span class="role-pill">Admin Métier</span></td>
                <td>Décisions, plans d'action, validation (dernier niveau), GED, rapports.</td>
                <td>Ne peut pas modifier les paramètres système.</td>
            </tr>
            <tr>
                <td><span class="role-pill">Validateur</span></td>
                <td>Validation (niveau 2), consultation des décisions et documents.</td>
                <td>Ne peut pas créer de décisions ni administrer.</td>
            </tr>
            <tr>
                <td><span class="role-pill">Point Focal</span></td>
                <td>Saisie des mises à jour, dépôt de documents, validation (niveau 1).</td>
                <td>Accès limité aux décisions confidentielles.</td>
            </tr>
            <tr>
                <td><span class="role-pill">Consultant</span></td>
                <td>Lecture seule sur les décisions et documents de son institution.</td>
                <td>Aucune action de modification ou validation.</td>
            </tr>
        </tbody>
    </table>

    <h3>1.3 Navigation</h3>
    <p>La barre latérale de gauche organise les modules en trois rubriques :</p>
    <ul>
        <li><strong>Pilotage</strong> — Tableau de bord et Assistant IA.</li>
        <li><strong>Planification et Suivi</strong> — Plans d'action, Mises à jour, Approbations, Rapports, GED.</li>
        <li><strong>Administration</strong> — Paramètres, utilisateurs, rôles.</li>
    </ul>
    <div class="box box-info">
        <div class="box-title">Navigation contextuelle</div>
        Le fil d'Ariane (breadcrumb) en haut de chaque page vous permet de vous repérer dans la hiérarchie
        et de revenir rapidement à la liste parente.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 2 — AUTHENTIFICATION
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 2</div>
        <h2>Authentification</h2>
    </div>

    <h3>2.1 Connexion à la plateforme</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Accédez à l'URL de la plateforme fournie par votre administrateur système.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Sur la page d'accueil publique, cliquez sur <strong>Connexion</strong> dans la barre de navigation.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Saisissez votre <strong>adresse e-mail</strong> et votre <strong>mot de passe</strong>.</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Cochez <em>« Se souvenir de moi »</em> pour rester connecté(e) 30 jours, puis cliquez sur <strong>Se connecter</strong>.</div></div>
        <div class="step"><div class="step-num">5</div><div class="step-body">Vous êtes redirigé(e) vers le <strong>Tableau de Bord Exécutif</strong>.</div></div>
    </div>

    <div class="box box-warning">
        <div class="box-title">Sécurité du compte</div>
        Ne partagez jamais vos identifiants. La session expire automatiquement après 120 minutes d'inactivité.
        En cas d'absence prolongée, cliquez sur votre nom en haut à droite puis <strong>Déconnexion</strong>.
    </div>

    <h3>2.2 Réinitialisation du mot de passe</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Cliquez sur <strong>Mot de passe oublié ?</strong> sur la page de connexion.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Saisissez votre adresse e-mail et cliquez sur <strong>Envoyer le lien de réinitialisation</strong>.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Ouvrez l'e-mail reçu et cliquez sur le lien (valide 60 minutes).</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Saisissez et confirmez votre nouveau mot de passe (minimum 8 caractères).</div></div>
    </div>

    <h3>2.3 Vérification d'e-mail</h3>
    <p>
        Lors de l'inscription, un e-mail de vérification est envoyé. Cliquez sur le lien pour activer votre compte.
        Sans vérification, l'accès aux modules internes est bloqué.
    </p>
    <div class="box box-tip">
        <div class="box-title">Compte créé par l'administrateur</div>
        Si votre compte a été créé directement par l'administrateur, vous recevez un e-mail d'invitation.
        Suivez le lien pour définir votre mot de passe.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 3 — TABLEAU DE BORD
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 3</div>
        <h2>Tableau de Bord Exécutif</h2>
    </div>

    <p>
        Le tableau de bord est la première page affichée après connexion. Il offre une vision consolidée
        et en temps réel de l'état d'avancement de toutes les décisions de la Commission.
    </p>

    <h3>3.1 Indicateurs clés (KPI)</h3>
    <table>
        <thead>
            <tr><th>Carte</th><th>Description</th></tr>
        </thead>
        <tbody>
            <tr><td><strong>Taux d'exécution global</strong></td><td>Moyenne du taux de progression sur l'ensemble des décisions actives.</td></tr>
            <tr><td><strong>Total Décisions</strong></td><td>Nombre total de décisions enregistrées dans le système.</td></tr>
            <tr><td><strong>Décisions complétées</strong></td><td>Nombre de décisions au statut « Terminé ».</td></tr>
            <tr><td><strong>Décisions en retard</strong></td><td>Nombre de décisions au statut « En retard ».</td></tr>
        </tbody>
    </table>

    <h3>3.2 Graphiques de suivi</h3>
    <ul>
        <li><strong>Répartition par domaine</strong> — Camembert illustrant la distribution des décisions par domaine thématique.</li>
        <li><strong>Répartition par statut</strong> — Histogramme des statuts (En cours, Terminé, En retard, En attente).</li>
        <li><strong>Top décisions prioritaires</strong> — Liste des décisions à priorité haute ou en retard, avec le taux d'avancement.</li>
    </ul>

    <div class="box box-info">
        <div class="box-title">Actions rapides</div>
        Depuis le tableau de bord, le bouton <strong>+ Nouvelle Décision</strong> vous amène directement
        au formulaire de création sans passer par le menu.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 4 — GESTION DES DÉCISIONS
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 4</div>
        <h2>Gestion des Décisions</h2>
    </div>

    <p>
        Les décisions sont le pivot central de SUIVI-DEC. Une décision représente un acte officiel
        (décision, résolution, compte-rendu…) émis par un organe de la CEEAC et nécessitant un suivi.
    </p>

    <h3>4.1 Créer une décision</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Cliquez sur <strong>Décisions</strong> dans le menu, puis sur <strong>+ Nouvelle Décision</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Remplissez les champs obligatoires : <strong>Titre</strong>, <strong>Type</strong>, <strong>Catégorie</strong>, <strong>Institution émettrice</strong>, <strong>Date d'adoption</strong>.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Renseignez optionnellement : Référence officielle, Domaine thématique, Session, Date d'échéance, Priorité, Niveau de confidentialité.</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Ajoutez le texte intégral dans la zone <strong>Contenu</strong> (supporte la mise en forme riche).</div></div>
        <div class="step"><div class="step-num">5</div><div class="step-body">Cliquez sur <strong>Prévisualiser</strong> pour vérifier le rendu, puis sur <strong>Enregistrer</strong>.</div></div>
    </div>

    <div class="box box-tip">
        <div class="box-title">Sauvegarde automatique</div>
        Le formulaire enregistre automatiquement un brouillon toutes les 60 secondes
        (fonctionnalité autosave). En cas de fermeture accidentelle, vous pouvez reprendre la saisie.
    </div>

    <h3>4.2 Consulter et modifier une décision</h3>
    <p>
        Depuis la liste des décisions, cliquez sur le titre pour accéder à la fiche détaillée.
        La fiche regroupe : informations générales, plan d'action associé, documents joints,
        historique de validation et journal de modifications.
    </p>
    <p>
        Le bouton <strong>Modifier</strong> (visible selon votre rôle) ouvre le formulaire d'édition.
        Les modifications sont tracées dans le journal d'audit.
    </p>

    <h3>4.3 Export CSV</h3>
    <p>
        Sur la liste des décisions, cliquez sur <strong>Exporter CSV</strong> pour télécharger l'ensemble
        des décisions au format tableur (UTF-8 BOM pour compatibilité Excel).
        Les filtres actifs sont pris en compte lors de l'export.
    </p>

    <h3>4.4 Statuts et priorités</h3>
    <table>
        <thead><tr><th>Valeur</th><th>Signification</th></tr></thead>
        <tbody>
            <tr><td colspan="2" style="background:#f0f4ff;font-weight:bold;color:#0b3d91;">Statuts</td></tr>
            <tr><td><span class="badge badge-gray">En attente</span></td><td>Décision créée, aucun plan d'action actif.</td></tr>
            <tr><td><span class="badge badge-blue">En cours</span></td><td>Mise en œuvre démarrée.</td></tr>
            <tr><td><span class="badge badge-green">Terminé</span></td><td>Toutes les activités sont réalisées.</td></tr>
            <tr><td><span class="badge badge-red">En retard</span></td><td>Date d'échéance dépassée sans complétion.</td></tr>
            <tr><td colspan="2" style="background:#f0f4ff;font-weight:bold;color:#0b3d91;">Priorités</td></tr>
            <tr><td><span class="badge badge-red">Critique</span></td><td>Priorité 1 — traitement immédiat requis.</td></tr>
            <tr><td><span class="badge badge-yellow">Haute</span></td><td>Priorité 2 — action rapide nécessaire.</td></tr>
            <tr><td><span class="badge badge-blue">Moyenne</span></td><td>Priorité 3 — traitement dans les délais normaux.</td></tr>
            <tr><td><span class="badge badge-gray">Basse</span></td><td>Priorité 4 — traitement différable.</td></tr>
        </tbody>
    </table>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 5 — PLANS D'ACTION
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 5</div>
        <h2>Plans d'Action &amp; Axes Stratégiques</h2>
    </div>

    <p>
        Chaque décision peut être déclinée en un <strong>Plan d'Action</strong> structuré en
        axes stratégiques, actions métiers et activités opérationnelles.
    </p>

    <h3>5.1 Créer un plan d'action</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Accédez à la fiche d'une décision, puis cliquez sur <strong>+ Créer un Plan d'Action</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Donnez un titre au plan, définissez la date de début et de fin globale.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Enregistrez le plan, puis ajoutez les <strong>Axes Stratégiques</strong> via le bouton dédié.</div></div>
    </div>

    <h3>5.2 Axes Stratégiques</h3>
    <p>
        Un axe stratégique représente une grande orientation thématique de la décision.
        Pour chaque axe, renseignez : <strong>Titre</strong>, <strong>Description</strong>, <strong>Priorité</strong>.
    </p>
    <p>
        Depuis la fiche d'un axe, ajoutez les <strong>Actions Métiers</strong> : objectifs opérationnels
        intermédiaires avec responsable présumé.
    </p>

    <h3>5.3 Actions et Activités</h3>
    <p>
        Chaque action métier se décompose en <strong>Activités</strong> planifiables :
    </p>
    <ul>
        <li><strong>Titre et description</strong> de l'activité.</li>
        <li><strong>Acteur responsable</strong> (direction ou agent).</li>
        <li><strong>Durée estimée</strong> (ex : « 3 mois », « continu »).</li>
        <li><strong>Jalons</strong> — étapes clés avec échéance et importance.</li>
        <li><strong>Livrables</strong> — résultats tangibles attendus (rapport, loi, budget…).</li>
        <li><strong>Indicateurs</strong> — métriques de mesure du résultat.</li>
    </ul>

    <div class="box box-info">
        <div class="box-title">Hiérarchie de la planification</div>
        <strong>Décision</strong> → <strong>Plan d'Action</strong> → <strong>Axe Stratégique</strong>
        → <strong>Action Métier</strong> → <strong>Activité</strong> → <strong>Jalons / Livrables</strong>
    </div>

    <h3>5.4 Affectation d'acteurs</h3>
    <p>
        Sur chaque activité, utilisez le bouton <strong>Assigner un Acteur</strong> pour lier
        un utilisateur ou une direction à l'activité. Les acteurs assignés reçoivent les notifications
        associées.
    </p>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 6 — SUIVI DE LA MISE EN ŒUVRE
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 6</div>
        <h2>Suivi de la Mise en Œuvre</h2>
    </div>

    <h3>6.1 Mises à jour de progrès</h3>
    <p>
        Les mises à jour de progrès permettent d'enregistrer l'avancement réel d'une activité
        ou d'un plan d'action à une date donnée.
    </p>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Dans le menu, cliquez sur <strong>Mises à jour</strong>, puis sur <strong>+ Nouvelle Mise à jour</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Sélectionnez le <strong>Plan d'Action</strong> concerné et la <strong>Date de la mise à jour</strong>.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Saisissez le <strong>pourcentage d'avancement</strong> (0–100%), les <strong>réalisations</strong> récentes et les <strong>obstacles éventuels</strong>.</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Enregistrez. La mise à jour est soumise au circuit de validation si elle dépasse un seuil configuré.</div></div>
    </div>

    <p>
        Une fois validée, la mise à jour met automatiquement à jour le <strong>taux de progression</strong>
        de la décision correspondante dans le tableau de bord.
    </p>

    <h4>Export PDF d'une mise à jour</h4>
    <p>
        Depuis la fiche d'une mise à jour, cliquez sur <strong>Exporter en PDF</strong>
        pour générer un rapport de progression au format PDF, incluant le contexte de la décision,
        les indicateurs et les commentaires.
    </p>

    <h3>6.2 Jalons</h3>
    <p>
        Les jalons sont des étapes clés associées à une activité. Ils permettent de marquer
        les dates importantes de la mise en œuvre. Chaque jalon dispose d'une <strong>échéance estimée</strong>
        et d'un niveau d'importance (<span class="badge badge-red">Critique</span>,
        <span class="badge badge-yellow">Moyen</span>, <span class="badge badge-blue">Faible</span>).
    </p>

    <h3>6.3 Livrables</h3>
    <p>
        Les livrables sont les résultats concrets d'une activité (rapport, texte réglementaire, budget…).
        Pour chaque livrable, précisez : <strong>titre</strong>, <strong>type</strong>,
        <strong>description</strong> et <strong>preuve attendue</strong>.
    </p>

    <h3>6.4 Indicateurs de Performance</h3>
    <p>
        Les indicateurs permettent de mesurer quantitativement l'atteinte des objectifs.
        Associez un indicateur à une activité en précisant la <strong>valeur cible</strong>,
        l'<strong>unité de mesure</strong> et la <strong>valeur réalisée</strong> lors des mises à jour.
    </p>

    <div class="box box-tip">
        <div class="box-title">Bonnes pratiques</div>
        Effectuez vos mises à jour de progrès chaque semaine ou à chaque jalon atteint.
        La régularité des saisies garantit la fiabilité du tableau de bord exécutif.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 7 — CIRCUIT DE VALIDATION
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 7</div>
        <h2>Circuit de Validation</h2>
    </div>

    <p>
        La plateforme intègre un <strong>circuit de validation à trois niveaux</strong>
        pour les mises à jour de progrès et la publication de documents GED.
    </p>

    <h3>7.1 Workflow d'approbation</h3>
    <div class="workflow">
        <span class="wf-step">Point Focal</span>
        <span class="wf-arrow">→</span>
        <span class="wf-step">Validateur</span>
        <span class="wf-arrow">→</span>
        <span class="wf-step">Admin Métier</span>
        <span class="wf-arrow">→</span>
        <span class="wf-step badge-green">Validé</span>
    </div>
    <p>
        Chaque étape est horodatée et tracée. Un commentaire peut être ajouté lors de chaque action.
    </p>

    <h3>7.2 Actions de validation</h3>
    <p>
        Accédez à <strong>Approbations</strong> dans le menu pour voir les éléments en attente de votre action.
        Pour chaque élément :
    </p>
    <ul>
        <li>Cliquez sur <strong>Approuver</strong> pour valider et passer à l'étape suivante.</li>
        <li>Cliquez sur <strong>Rejeter</strong> pour renvoyer à l'émetteur avec un commentaire obligatoire.</li>
    </ul>

    <div class="box box-warning">
        <div class="box-title">Commentaire de rejet obligatoire</div>
        Tout rejet doit être motivé. Le champ commentaire est obligatoire lors d'un rejet
        pour permettre à l'émetteur de corriger et resoumettre.
    </div>

    <h3>7.3 Cas de rejet et resoumission</h3>
    <p>
        Lorsqu'une mise à jour est rejetée, son statut passe à <span class="badge badge-red">Rejeté</span>.
        L'émetteur (Point Focal) reçoit une notification et peut corriger sa saisie avant de la resoumettre
        au circuit depuis la liste des mises à jour.
    </p>

    <table>
        <thead><tr><th>Statut</th><th>Description</th><th>Action disponible</th></tr></thead>
        <tbody>
            <tr><td><span class="badge badge-gray">En attente</span></td><td>Soumis, pas encore traité.</td><td>Approuver / Rejeter</td></tr>
            <tr><td><span class="badge badge-blue">En cours</span></td><td>Traité au niveau intermédiaire.</td><td>Approuver / Rejeter</td></tr>
            <tr><td><span class="badge badge-green">Validé</span></td><td>Approuvé à tous les niveaux.</td><td>Lecture seule</td></tr>
            <tr><td><span class="badge badge-red">Rejeté</span></td><td>Renvoyé pour correction.</td><td>Correction et resoumission</td></tr>
        </tbody>
    </table>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 8 — GED
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 8</div>
        <h2>GED — Gestion Électronique des Documents</h2>
    </div>

    <p>
        Le module GED centralise l'ensemble des documents liés aux décisions et activités de la CEEAC.
        Il offre le versioning, la recherche avancée, la gestion des accès et un workflow de validation documentaire.
    </p>

    <h3>8.1 Dépôt d'un document</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Accédez à la fiche d'une décision ou d'une activité et localisez la section <strong>Documents</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Cliquez sur <strong>+ Ajouter un document</strong>.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Sélectionnez le fichier (formats acceptés : PDF, DOCX, XLSX, PPTX, ODT, ODS, TXT, CSV, JPG, PNG). Taille max : 20 Mo.</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Renseignez : <strong>Titre</strong>, <strong>Catégorie documentaire</strong>, <strong>Niveau de confidentialité</strong>, <strong>Tags</strong> (mots-clés séparés par des virgules).</div></div>
        <div class="step"><div class="step-num">5</div><div class="step-body">Cliquez sur <strong>Enregistrer</strong>. Le document est automatiquement déposé en <span class="badge badge-gray">Brouillon</span>.</div></div>
    </div>

    <div class="box box-info">
        <div class="box-title">Déduplication automatique</div>
        Si vous déposez un fichier identique à un document existant (même empreinte SHA-256),
        le système vous en informe et ne crée pas de doublon.
    </div>

    <h3>8.2 Recherche et filtres dans la GED</h3>
    <p>Accédez à <strong>GED</strong> dans le menu pour consulter tous les documents. Les filtres disponibles :</p>
    <table>
        <thead><tr><th>Filtre</th><th>Description</th></tr></thead>
        <tbody>
            <tr><td>Catégorie</td><td>Filtre par type documentaire.</td></tr>
            <tr><td>Statut de workflow</td><td>Brouillon, En validation, Validé, Rejeté.</td></tr>
            <tr><td>Décision associée</td><td>Documents liés à une décision spécifique.</td></tr>
            <tr><td>Type MIME</td><td>Filtre par format de fichier (PDF, Word…).</td></tr>
            <tr><td>Confidentialité</td><td>Filtre par niveau d'accès.</td></tr>
            <tr><td>Service auteur</td><td>Direction ou service ayant déposé le document.</td></tr>
            <tr><td>Période</td><td>Date de création entre deux dates.</td></tr>
        </tbody>
    </table>

    <h3>8.3 Versioning des documents</h3>
    <p>
        Depuis la fiche d'un document dans la GED (<strong>GED → Titre du document</strong>),
        cliquez sur <strong>Déposer une nouvelle version</strong> pour téléverser un fichier révisé.
        Indiquez la <strong>raison du changement</strong> et si c'est une <strong>version majeure</strong>.
    </p>
    <p>
        L'historique complet des versions est consultable dans la section <em>Historique des versions</em>
        de la fiche document. Chaque version affiche : numéro, auteur, taille, date, raison du changement.
    </p>

    <h3>8.4 Workflow de validation documentaire</h3>
    <p>
        Pour soumettre un document au circuit de validation, cliquez sur
        <strong>Lancer la validation</strong> depuis la fiche du document.
        Le circuit suit les mêmes niveaux que la validation des mises à jour
        (Point Focal → Validateur → Admin Métier).
    </p>
    <p>
        Les validateurs accèdent à la file d'attente via <strong>GED → Validations Documentaires</strong>.
    </p>

    <h3>8.5 Export CSV de la GED</h3>
    <p>
        Sur la liste GED, le bouton <strong>Exporter CSV</strong> génère un fichier tableur incluant
        tous les métadonnées des documents filtrés (titre, catégorie, auteur, statut, taille, date).
    </p>

    <div class="box box-warning">
        <div class="box-title">Suppression logique</div>
        La suppression d'un document est logique (soft delete) : le fichier reste archivé et consultable
        par les administrateurs, mais disparaît des listes courantes.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 9 — ASSISTANT IA
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 9</div>
        <h2>Assistant IA — Analyse de Décisions</h2>
    </div>

    <p>
        L'Assistant IA exploite le modèle <strong>GPT-4o d'OpenAI</strong> pour analyser automatiquement
        le texte d'un acte institutionnel et en extraire une structure de suivi-évaluation complète
        (axes, actions, activités, jalons, livrables).
    </p>

    <h3>9.1 Lancer une analyse IA</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Dans le menu, cliquez sur <strong>Assistant IA</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Sélectionnez le <strong>Type de document</strong> (Décision, Résolution, Rapport, Compte-rendu…).</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Collez le texte intégral de l'acte dans la zone de saisie (50 à 80 000 caractères).</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Cliquez sur <strong>Analyser le document</strong>. L'analyse prend entre 15 et 60 secondes selon la longueur du texte.</div></div>
        <div class="step"><div class="step-num">5</div><div class="step-body">Les résultats s'affichent sur la page suivante.</div></div>
    </div>

    <div class="box box-warning">
        <div class="box-title">Limite d'utilisation</div>
        L'analyse est limitée à <strong>5 requêtes par heure et par utilisateur</strong> pour préserver
        les quotas API. En cas de dépassement, un message d'erreur vous invite à patienter.
    </div>

    <h3>9.2 Interpréter les résultats</h3>
    <p>La page de résultats affiche :</p>
    <ul>
        <li><strong>Résumé</strong> — synthèse de l'acte en 3 lignes.</li>
        <li><strong>Score de confiance</strong> — pourcentage indiquant la certitude de l'IA (0–100).</li>
        <li><strong>Axes stratégiques</strong> (accordéon) — avec pour chaque axe ses actions, activités, jalons et livrables.</li>
        <li><strong>Avertissements</strong> — hypothèses formulées par l'IA (responsable non identifié, date vague…).</li>
    </ul>

    <div class="box box-info">
        <div class="box-title">Mode Simulation</div>
        En l'absence de connexion à l'API OpenAI (clé absente ou service indisponible),
        le système génère des données fictives clairement identifiées par le badge
        <span class="badge badge-yellow">MODE SIMULATION</span>.
        Ces données ne doivent pas être importées dans la base de données.
    </div>

    <h3>9.3 Importer les résultats dans la base</h3>
    <p>
        Si les résultats sont satisfaisants, cliquez sur <strong>Confirmer et Importer</strong>.
        La plateforme va automatiquement :
    </p>
    <ul>
        <li>Créer une <strong>Décision</strong> avec les métadonnées extraites.</li>
        <li>Créer un <strong>Plan d'Action</strong> associé.</li>
        <li>Créer les <strong>Axes Stratégiques</strong>, <strong>Actions</strong> et <strong>Activités</strong>.</li>
        <li>Créer les <strong>Jalons</strong> et <strong>Livrables</strong> pour chaque activité.</li>
    </ul>
    <p>
        Une fois importée, la structure est consultable et modifiable dans le module Décisions.
        Une analyse importée ne peut plus être réimportée (statut <span class="badge badge-green">Finalisé</span>).
    </p>

    <h3>9.4 Limites et recommandations</h3>
    <div class="box box-danger">
        <div class="box-title">Vérification humaine obligatoire</div>
        L'IA peut commettre des erreurs d'interprétation ou formuler des hypothèses non fondées
        (indiquées dans les avertissements). <strong>Relisez toujours les résultats avant de confirmer l'import</strong>.
        Les responsables présumés et les dates estimées doivent être vérifiés et corrigés si nécessaire.
    </div>
    <ul>
        <li>Textes trop courts (&lt; 200 mots) : l'IA produira une analyse peu structurée.</li>
        <li>Textes non-institutionnels : résultats non pertinents.</li>
        <li>Langues : l'IA traite le français et l'anglais. Les textes en d'autres langues peuvent produire des résultats dégradés.</li>
    </ul>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 10 — RAPPORTS
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 10</div>
        <h2>Rapports Stratégiques</h2>
    </div>

    <p>
        SUIVI-DEC génère des rapports PDF prêts à imprimer ou à diffuser, consolidant les données
        de suivi des décisions et plans d'action.
    </p>

    <h3>10.1 Rapport Stratégique Global</h3>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Cliquez sur <strong>Rapports Stratégiques</strong> dans le menu.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Consultez l'aperçu des indicateurs (total, complétées, en retard, taux moyen, répartition par domaine).</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Cliquez sur <strong>Télécharger le rapport PDF</strong>. Le PDF est généré et téléchargé automatiquement.</div></div>
    </div>

    <p>
        Le rapport PDF inclut : indicateurs clés de performance, tableau des décisions prioritaires,
        répartition par domaine, liste des décisions en retard avec taux d'avancement.
    </p>

    <h3>10.2 Rapport de Progression (par mise à jour)</h3>
    <p>
        Depuis la fiche d'une mise à jour de progrès, le bouton <strong>Exporter en PDF</strong>
        génère un rapport individuel : contexte de la décision, avancement chiffré, réalisations,
        obstacles et recommandations.
    </p>

    <div class="box box-tip">
        <div class="box-title">Diffusion des rapports</div>
        Les rapports PDF générés sont horodatés et incluent le nom de la Commission.
        Ils peuvent être directement joints aux comptes-rendus de réunion ou archivés dans la GED.
    </div>

    <h3>10.3 Manuel Utilisateur</h3>
    <p>
        Ce document est également disponible en téléchargement depuis le menu Administration.
        Cliquez sur <strong>Télécharger le Manuel</strong> pour obtenir la version PDF à jour.
    </p>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 11 — ADMINISTRATION
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 11</div>
        <h2>Administration &amp; Paramétrage</h2>
    </div>

    <p>
        La zone Administration est réservée aux utilisateurs ayant le rôle
        <span class="role-pill">Administrateur</span> ou <span class="role-pill">Admin Métier</span>.
    </p>

    <h3>11.1 Types et Catégories de Décisions</h3>
    <table>
        <thead><tr><th>Objet</th><th>Exemples</th><th>Accès via</th></tr></thead>
        <tbody>
            <tr><td><strong>Types</strong></td><td>Décision, Résolution, Recommandation, Règlement, Directive</td><td>Admin → Types de décisions</td></tr>
            <tr><td><strong>Catégories</strong></td><td>Institutionnelle, Budgétaire, Sécuritaire, Économique</td><td>Admin → Catégories de décisions</td></tr>
            <tr><td><strong>Domaines</strong></td><td>Paix &amp; Sécurité, Intégration Économique, Éducation…</td><td>Admin → Domaines</td></tr>
            <tr><td><strong>Sessions</strong></td><td>Sommet des Chefs d'État, Conseil des Ministres…</td><td>Admin → Sessions</td></tr>
        </tbody>
    </table>

    <h3>11.2 Organisation Institutionnelle</h3>
    <p>
        Le paramétrage institutionnel modélise la structure de la CEEAC :
    </p>
    <ul>
        <li><strong>Pays membres</strong> — liste des États membres.</li>
        <li><strong>Institutions</strong> — organes institutionnels (Commission, Parlement, Cour…).</li>
        <li><strong>Organes</strong> — subdivisions (Conseil des Ministres, Conférence des Chefs…).</li>
        <li><strong>Départements et Directions</strong> — services internes de la Commission.</li>
    </ul>

    <h3>11.3 Gestion des Utilisateurs et Rôles</h3>
    <p><strong>Créer un utilisateur :</strong></p>
    <div class="steps">
        <div class="step"><div class="step-num">1</div><div class="step-body">Allez dans <strong>Admin → Utilisateurs → + Nouvel utilisateur</strong>.</div></div>
        <div class="step"><div class="step-num">2</div><div class="step-body">Renseignez nom, e-mail et mot de passe temporaire.</div></div>
        <div class="step"><div class="step-num">3</div><div class="step-body">Associez un ou plusieurs <strong>rôles</strong> et une <strong>institution</strong>.</div></div>
        <div class="step"><div class="step-num">4</div><div class="step-body">Enregistrez. L'utilisateur recevra un e-mail d'invitation.</div></div>
    </div>

    <p><strong>Gestion des rôles :</strong> Depuis <strong>Admin → Rôles</strong>, créez des rôles personnalisés
    avec des libellés spécifiques à votre organisation. Associez les rôles aux utilisateurs depuis la fiche utilisateur.</p>

    <div class="box box-warning">
        <div class="box-title">Principe du moindre privilège</div>
        N'attribuez que les rôles strictement nécessaires. Un rôle de Consultant suffit
        pour un membre qui n'a besoin que de consulter les décisions.
    </div>

    <h3>11.4 Journal d'Audit</h3>
    <p>
        Accessible via <strong>Admin → Journal d'Audit</strong>, ce journal trace automatiquement
        toutes les actions significatives : création, modification, suppression de décisions et documents,
        connexions et déconnexions, actions de validation, accès aux documents confidentiels.
    </p>
    <p>
        Pour chaque entrée : date/heure, utilisateur, type d'action, objet affecté, adresse IP.
    </p>

    <div class="box box-info">
        <div class="box-title">Traçabilité réglementaire</div>
        Le journal d'audit est non modifiable. Il constitue la trace officielle des opérations
        pour toute procédure de contrôle interne ou d'audit externe.
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SECTION 12 — GLOSSAIRE & FAQ
     ══════════════════════════════════════════════ -->
<div class="section">
    <div class="section-header">
        <div class="section-num">Section 12</div>
        <h2>Glossaire &amp; Questions Fréquentes</h2>
    </div>

    <h3>12.1 Glossaire</h3>
    <table>
        <thead><tr><th>Terme</th><th>Définition</th></tr></thead>
        <tbody>
            <tr><td><strong>GED</strong></td><td>Gestion Électronique des Documents — module d'archivage numérique.</td></tr>
            <tr><td><strong>KPI</strong></td><td>Key Performance Indicator — indicateur clé de performance.</td></tr>
            <tr><td><strong>Livrable</strong></td><td>Résultat tangible et mesurable d'une activité.</td></tr>
            <tr><td><strong>Jalon</strong></td><td>Étape clé d'une activité avec une date d'échéance.</td></tr>
            <tr><td><strong>Axe stratégique</strong></td><td>Grande orientation thématique d'un plan d'action.</td></tr>
            <tr><td><strong>Action métier</strong></td><td>Objectif opérationnel intermédiaire rattaché à un axe.</td></tr>
            <tr><td><strong>Soft delete</strong></td><td>Suppression logique — l'enregistrement est masqué mais non effacé définitivement.</td></tr>
            <tr><td><strong>Versioning</strong></td><td>Conservation de l'historique de toutes les versions d'un document.</td></tr>
            <tr><td><strong>Workflow</strong></td><td>Circuit de traitement structuré passant par plusieurs niveaux d'approbation.</td></tr>
            <tr><td><strong>GPT-4o</strong></td><td>Modèle de langage d'OpenAI utilisé pour l'analyse IA des documents.</td></tr>
            <tr><td><strong>CEEAC</strong></td><td>Communauté Économique des États de l'Afrique Centrale.</td></tr>
            <tr><td><strong>Point Focal</strong></td><td>Agent de terrain chargé de la saisie des mises à jour et dépôts de documents.</td></tr>
        </tbody>
    </table>

    <h3>12.2 Questions Fréquentes</h3>

    <h4>Je ne peux pas me connecter — que faire ?</h4>
    <p>Vérifiez votre adresse e-mail et votre mot de passe. Si le problème persiste, utilisez
    la fonctionnalité <em>Mot de passe oublié</em>. Si votre compte n'est pas encore vérifié,
    consultez votre boîte e-mail.</p>

    <h4>L'analyse IA affiche « MODE SIMULATION » — est-ce normal ?</h4>
    <p>Cela signifie que l'API OpenAI n'a pas pu être contactée (clé absente, quota dépassé, ou service
    temporairement indisponible). Contactez votre administrateur. Les données simulées ne doivent pas être importées.</p>

    <h4>Mon document a été rejeté — comment le corriger ?</h4>
    <p>Consultez le commentaire de rejet dans la fiche du document ou de la mise à jour.
    Effectuez les corrections puis resoumettez au circuit de validation.</p>

    <h4>Comment modifier une décision déjà créée ?</h4>
    <p>Accédez à la fiche de la décision et cliquez sur <strong>Modifier</strong> (visible selon votre rôle).
    Toutes les modifications sont tracées dans le journal d'audit.</p>

    <h4>Quelle est la taille maximale d'un fichier en GED ?</h4>
    <p>20 Mo par fichier. Pour les fichiers plus volumineux, contactez l'administrateur système.</p>

    <h4>Puis-je supprimer une décision ?</h4>
    <p>La suppression est une action réservée aux administrateurs. Elle est logique (soft delete) :
    la décision disparaît des listes mais reste récupérable. Contactez votre administrateur si nécessaire.</p>

    <h4>L'export CSV est vide — pourquoi ?</h4>
    <p>Vérifiez que des filtres actifs ne restreignent pas les résultats à zéro enregistrement.
    Réinitialisez les filtres avant d'exporter.</p>

    <hr class="divider">
    <p style="text-align:center; color:#718096; font-size:9pt; margin-top:20px;">
        Pour toute question technique, contactez le support informatique de la Commission de la CEEAC.<br>
        <strong>SUIVI-DEC v1.0</strong> — Commission de la CEEAC — {{ date('Y') }}
    </p>
</div>

</body>
</html>
