<?php

namespace App\Support;

class InstitutionalText
{
    public static function pick(array $items): string
    {
        return $items[array_rand($items)];
    }

    public static function legalBasis(): string
    {
        return self::pick([
            "Traite revise de la CEEAC, article 6 relatif a la cooperation sectorielle.",
            "Reglement interieur du Conseil des ministres, dispositions sur le suivi des decisions.",
            "Protocole sur la paix et la securite en Afrique centrale, articles d'application.",
            "Cadre strategique communautaire 2024-2030, axe de gouvernance institutionnelle.",
            "Directive sur la coordination des politiques publiques regionales.",
        ]);
    }

    public static function decisionTitle(string $domain): string
    {
        return self::pick([
            "Mise en oeuvre de la feuille de route regionale en matiere de {$domain}",
            "Renforcement du mecanisme communautaire de suivi des engagements en {$domain}",
            "Harmonisation des instruments techniques pour la coordination en {$domain}",
            "Adoption de mesures prioritaires de consolidation en {$domain}",
        ]);
    }

    public static function shortSummary(): string
    {
        return self::pick([
            "Decision visant a accelerer l'execution des actions prioritaires et a renforcer la redevabilite des parties prenantes.",
            "Mesure de gouvernance destinee a structurer le pilotage des activites et la consolidation des resultats.",
            "Orientation operationnelle pour harmoniser les pratiques de suivi, validation et reporting periodique.",
            "Dispositif institutionnel pour reduire les retards et securiser les jalons critiques.",
        ]);
    }

    public static function longDescription(): string
    {
        return self::pick([
            "La presente decision definit les responsabilites institutionnelles, les delais de mise en oeuvre et les mecanismes de validation. Elle impose un suivi trimestriel, la remontee des risques et la formalisation des mesures correctives.",
            "Ce dispositif precise les resultats attendus, les indicateurs de performance et les obligations de reporting. Chaque entite responsable produit un point de situation periodique accompagne des pieces justificatives pertinentes.",
            "Le cadre d'execution retient une approche graduelle avec priorisation des actions critiques. Les structures chargees de l'implementation sont tenues de documenter les ecarts, contraintes et besoins d'appui.",
        ]);
    }

    public static function achievement(): string
    {
        return self::pick([
            "Finalisation de la note technique de cadrage et validation interne par le comite sectoriel.",
            "Tenue de deux sessions de concertation avec les points focaux nationaux et consolidation des observations.",
            "Production du rapport intermediaire avec indicateurs de performance actualises.",
            "Mise en service du mecanisme de collecte des donnees et alignement des formats de reporting.",
        ]);
    }

    public static function difficulty(): string
    {
        return self::pick([
            "Retard de transmission des donnees nationales dans les delais convenus.",
            "Disponibilite limitee des experts sectoriels pour les revues techniques conjointes.",
            "Contraintes budgetaires sur certaines activites de deploiement regional.",
            "Dependance a des validations interinstitutionnelles non encore finalisees.",
        ]);
    }

    public static function nextStep(): string
    {
        return self::pick([
            "Soumettre le projet de note d'arbitrage au secretariat general avant la prochaine revue mensuelle.",
            "Lancer la phase pilote dans trois Etats membres et capitaliser les enseignements.",
            "Programmer la mission conjointe de verification terrain et mettre a jour le plan de mitigation.",
            "Finaliser le paquet documentaire de conformite pour validation de niveau 2.",
        ]);
    }

    public static function supportNeed(): string
    {
        return self::pick([
            "Appui technique en analyse de donnees pour la consolidation regionale des indicateurs.",
            "Facilitation diplomatique pour accelerer les arbitrages interinstitutionnels.",
            "Renforcement ponctuel de l'enveloppe logistique pour la tenue des ateliers nationaux.",
            "Assistance juridique pour finaliser les instruments d'application.",
        ]);
    }

    public static function validationComment(string $status): string
    {
        if ($status === 'approved') {
            return self::pick([
                "Rapport conforme aux evidences fournies. Validation accordee sous reserve de suivi du prochain jalon.",
                "Les livrables annonces sont coherents avec les objectifs du trimestre. Avis favorable.",
                "La progression declaree est recevable et documentee. Validation confirmee.",
            ]);
        }

        return self::pick([
            "Elements probants insuffisants pour justifier le niveau d'avancement declare.",
            "Incoherence constatee entre les resultats annonces et les pieces justificatives jointes.",
            "Des precisions sont attendues sur le traitement des risques et le calendrier revise.",
        ]);
    }
}
