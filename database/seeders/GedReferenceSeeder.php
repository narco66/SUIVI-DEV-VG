<?php

namespace Database\Seeders;

use App\Models\ConfidentialityLevel;
use App\Models\DecisionStatus;
use App\Models\DocumentCategory;
use App\Models\DocumentStatus;
use App\Models\RetentionRule;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class GedReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'acte_signe', 'name' => 'Acte signé'],
            ['code' => 'projet_acte', 'name' => 'Projet d\'acte'],
            ['code' => 'version_travail', 'name' => 'Version de travail'],
            ['code' => 'rapport_session', 'name' => 'Rapport de session'],
            ['code' => 'compte_rendu', 'name' => 'Compte rendu'],
            ['code' => 'piece_justificative', 'name' => 'Pièce justificative'],
            ['code' => 'annexe', 'name' => 'Annexe'],
            ['code' => 'note_explicative', 'name' => 'Note explicative'],
            ['code' => 'correspondance', 'name' => 'Correspondance'],
            ['code' => 'archive_historique', 'name' => 'Archive historique'],
            ['code' => 'traduction', 'name' => 'Traduction'],
            ['code' => 'version_consolidee', 'name' => 'Version consolidée'],
            ['code' => 'version_publiee', 'name' => 'Version publiée'],
            ['code' => 'autre', 'name' => 'Autre'],
        ];

        foreach ($categories as $category) {
            DocumentCategory::updateOrCreate(['code' => $category['code']], $category);
        }

        $statuses = [
            ['code' => 'draft', 'name' => 'Brouillon', 'color_class' => 'secondary'],
            ['code' => 'in_review', 'name' => 'En revue', 'color_class' => 'warning'],
            ['code' => 'validated', 'name' => 'Validé', 'color_class' => 'info'],
            ['code' => 'published', 'name' => 'Publié', 'color_class' => 'success'],
            ['code' => 'archived', 'name' => 'Archivé', 'color_class' => 'dark'],
            ['code' => 'obsolete', 'name' => 'Obsolète', 'color_class' => 'danger'],
        ];

        foreach ($statuses as $status) {
            DocumentStatus::updateOrCreate(['code' => $status['code']], $status);
        }

        $levels = [
            ['code' => 'public', 'name' => 'Public', 'rank' => 1],
            ['code' => 'interne', 'name' => 'Interne', 'rank' => 2],
            ['code' => 'confidentiel', 'name' => 'Confidentiel', 'rank' => 3],
            ['code' => 'tres_confidentiel', 'name' => 'Très confidentiel', 'rank' => 4],
        ];

        foreach ($levels as $level) {
            ConfidentialityLevel::updateOrCreate(['code' => $level['code']], $level);
        }

        $decisionStatuses = [
            ['code' => 'draft', 'name' => 'Brouillon'],
            ['code' => 'pending_validation', 'name' => 'En validation'],
            ['code' => 'active', 'name' => 'Active'],
            ['code' => 'in_progress', 'name' => 'En exécution'],
            ['code' => 'completed', 'name' => 'Achevée'],
            ['code' => 'closed', 'name' => 'Clôturée'],
        ];

        foreach ($decisionStatuses as $status) {
            DecisionStatus::updateOrCreate(['code' => $status['code']], $status);
        }

        StorageLocation::updateOrCreate(
            ['name' => 'Stockage principal privé'],
            ['disk' => 'local', 'base_path' => 'ged', 'is_default' => true]
        );

        RetentionRule::updateOrCreate(
            ['name' => 'Standard 10 ans'],
            ['retention_years' => 10, 'is_permanent' => false]
        );
    }
}