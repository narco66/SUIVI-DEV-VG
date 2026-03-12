<?php

namespace Database\Seeders;

use App\Models\DecisionCategory;
use App\Models\DecisionType;
use App\Models\Domain;
use App\Models\Organ;
use Illuminate\Database\Seeder;

class GovernanceReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $conference = Organ::updateOrCreate(
            ['code' => 'ORG-CCE'],
            ['name' => 'Conference des Chefs d Etat et de Gouvernement', 'level' => 1, 'parent_id' => null]
        );

        $conseil = Organ::updateOrCreate(
            ['code' => 'ORG-CDM'],
            ['name' => 'Conseil des Ministres', 'level' => 2, 'parent_id' => $conference->id]
        );

        $comite = Organ::updateOrCreate(
            ['code' => 'ORG-CCP'],
            ['name' => 'Comite Consultatif Permanent', 'level' => 3, 'parent_id' => $conseil->id]
        );

        Organ::updateOrCreate(
            ['code' => 'ORG-SG'],
            ['name' => 'Secretariat General', 'level' => 2, 'parent_id' => $conference->id]
        );

        Organ::updateOrCreate(
            ['code' => 'ORG-CJ'],
            ['name' => 'Cour de Justice Communautaire', 'level' => 2, 'parent_id' => $conference->id]
        );

        Organ::updateOrCreate(
            ['code' => 'ORG-CDEF'],
            ['name' => 'Comite des Experts Financiers', 'level' => 4, 'parent_id' => $comite->id]
        );

        $types = [
            ['code' => 'DEC', 'name' => 'Decision', 'description' => "Acte obligatoire adopte par l'instance competente."],
            ['code' => 'REG', 'name' => 'Reglement', 'description' => "Norme d'application generale au niveau communautaire."],
            ['code' => 'REC', 'name' => 'Recommandation', 'description' => "Orientation formulee pour harmoniser les politiques nationales."],
            ['code' => 'RAP', 'name' => 'Rapport', 'description' => "Document officiel de redevabilite et de suivi des performances."],
            ['code' => 'COM', 'name' => 'Communique final', 'description' => "Synthese des deliberations et des engagements adoptes."],
            ['code' => 'ACT', 'name' => 'Acte', 'description' => "Instrument juridique ou administratif formalise."],
        ];

        foreach ($types as $type) {
            DecisionType::updateOrCreate(['code' => $type['code']], $type);
        }

        $categories = [
            ['code' => 'SEC', 'name' => 'Paix et securite', 'description' => 'Prevention des conflits et securite regionale.'],
            ['code' => 'ECO', 'name' => 'Integration economique', 'description' => 'Commerce, infrastructures et integration des marches.'],
            ['code' => 'GOU', 'name' => 'Gouvernance institutionnelle', 'description' => 'Pilotage institutionnel et coordination intersectorielle.'],
            ['code' => 'SOC', 'name' => 'Developpement social', 'description' => 'Education, sante, protection sociale et inclusion.'],
            ['code' => 'ENV', 'name' => 'Environnement et climat', 'description' => 'Transition verte et resilience climatique.'],
        ];

        foreach ($categories as $category) {
            DecisionCategory::updateOrCreate(['code' => $category['code']], $category);
        }

        $domainRoots = [
            ['code' => 'DOM-PAIX', 'name' => 'Paix et securite', 'description' => 'Prevention, mediation, operations de soutien.'],
            ['code' => 'DOM-ECO', 'name' => 'Economique et infrastructures', 'description' => 'Commerce, energie, transport, numerique.'],
            ['code' => 'DOM-SOC', 'name' => 'Developpement humain', 'description' => 'Sante, education, emploi, mobilite.'],
            ['code' => 'DOM-GOUV', 'name' => 'Gouvernance et reformes', 'description' => 'Efficacite institutionnelle et transparence.'],
        ];

        $domainIds = [];
        foreach ($domainRoots as $root) {
            $domain = Domain::updateOrCreate(['code' => $root['code']], $root + ['parent_id' => null]);
            $domainIds[$root['code']] = $domain->id;
        }

        $subDomains = [
            ['code' => 'DOM-PAIX-ALR', 'name' => 'Alerte precoce', 'parent_code' => 'DOM-PAIX'],
            ['code' => 'DOM-PAIX-FRONT', 'name' => 'Cooperation frontaliere', 'parent_code' => 'DOM-PAIX'],
            ['code' => 'DOM-ECO-DOU', 'name' => 'Facilitation douaniere', 'parent_code' => 'DOM-ECO'],
            ['code' => 'DOM-ECO-TRAN', 'name' => 'Corridors de transport', 'parent_code' => 'DOM-ECO'],
            ['code' => 'DOM-SOC-SAN', 'name' => 'Sante publique', 'parent_code' => 'DOM-SOC'],
            ['code' => 'DOM-GOUV-REP', 'name' => 'Reformes administratives', 'parent_code' => 'DOM-GOUV'],
        ];

        foreach ($subDomains as $sub) {
            Domain::updateOrCreate(
                ['code' => $sub['code']],
                [
                    'name' => $sub['name'],
                    'description' => 'Sous-domaine de travail institutionnel.',
                    'parent_id' => $domainIds[$sub['parent_code']] ?? null,
                ]
            );
        }
    }
}

