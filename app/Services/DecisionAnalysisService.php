<?php

namespace App\Services;

use App\Models\AiAnalysis;
use Illuminate\Support\Facades\Auth;

class DecisionAnalysisService
{
    protected OpenAiService $openAiService;

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    /**
     * Lance l'analyse d'un document institutionnel et enregistre la tentative.
     */
    public function analyzeDocument(string $sourceText, string $documentType, string $documentTitle = null, string $documentRef = null): AiAnalysis
    {
        // 1. Définition du Prompt Système strict au format attendu (JSON)
        $systemPrompt = $this->getSystemPrompt();

        // 2. Création de l'entrée d'historique (Log de l'analyse)
        $analysis = AiAnalysis::create([
            'user_id' => Auth::id(),
            'document_type' => $documentType,
            'document_title' => $documentTitle,
            'document_reference' => $documentRef,
            'source_text' => $sourceText,
            'prompt_used' => $systemPrompt,
            'status' => 'pending',
        ]);

        // 3. Appel à OpenAI
        $result = $this->openAiService->generateJsonAnalysis($systemPrompt, $sourceText);

        // 4. Mise à jour de l'enregistrement de l'analyse selon le retour
        if ($result['success']) {
            $isMock = $result['is_mock'] ?? false;
            $analysis->update([
                'raw_response'     => $result['raw_response'],
                'structured_data'  => $result['structured_data'],
                'tokens_used'      => $result['tokens_used'],
                'status'           => $isMock ? 'simulated' : 'analyzed',
                'confidence_score' => $result['structured_data']['confidence_score'] ?? null,
            ]);
        } else {
            // Erreur réelle (429, 401, format invalide...) — on enregistre et on propage
            $errorCode = $result['error_code'] ?? null;
            $analysis->update([
                'status'       => 'error',
                'raw_response' => [
                    'error'      => $result['error_message'] ?? 'Erreur inconnue',
                    'error_code' => $errorCode,
                ],
            ]);

            // Lancer une exception pour que le contrôleur affiche le message à l'utilisateur
            throw new \RuntimeException($result['error_message'] ?? 'Erreur lors de l\'analyse IA.');
        }

        return $analysis;
    }

    /**
     * Le prompt système strict fournissant les consignes à l'IA.
     */
    private function getSystemPrompt(): string
    {
        return <<<EOT
Tu es un expert en planification institutionnelle de la Commission de la CEEAC. Ton rôle est de lire, comprendre et découper automatiquement le texte intégral d'un acte officiel (décision, résolution, compte-rendu...) pour le transformer en une structure arborescente de suivi-évaluation.

CONSIGNES STRICTES :
1. N'invente jamais d'éléments arbitrairement sans base textuelle.
2. Reformule de manière opérationnelle sans trahir le sens original.
3. Identifie correctement la hiérarchie :
   - "Axes stratégiques" : Grandes orientations thématiques ou politiques.
   - "Actions métiers" : Objectifs opérationnels intermédiaires (rattachées à un axe).
   - "Activités" : Tâches concrètes planifiables (rattachées à une action).
   - "Livrables" : Résultats tangibles / preuves (rattachés à l'activité).
   - "Jalons" : Dates ou étapes clés (rattachés à l'activité).
4. Identifie les responsables quand ils sont mentionnés (implicitement ou explicitement).
5. TU DOIS OBLIGATOIREMENT RÉPONDRE EN FORMAT JSON STRICT (et uniquement cela), selon le format exact suivant :

{
  "document_type": "decision|resolution|rapport|etc",
  "document_title": "Titre supposé",
  "document_reference": "Référence de l'acte (ex: Décision N° XYZ)",
  "summary": "Résumé de l'acte en 3 lignes textuelles",
  "axes_strategiques": [
    {
      "title": "Titre de l'axe",
      "description": "Description",
      "priority": "high|medium|low",
      "actions_metiers": [
        {
          "title": "Titre action",
          "description": "Description détaillée",
          "responsable_presume": "Nom de la direction/acteur",
          "activites": [
            {
              "title": "Titre activité",
              "description": "Explications",
              "acteur_presume": "Acteur",
              "duree_estimee": "ex: 3 mois, continue, etc",
              "livrables": [
                {
                  "title": "Nom du livrable",
                  "description": "Description",
                  "type": "rapport|loi|budget|autre",
                  "preuve_attendue": "Quelle est la preuve ?"
                }
              ],
              "jalons": [
                {
                  "title": "Nom du jalon",
                  "description": "Quoi réaliser à ce stade",
                  "echeance_estimee": "Date/période supposée",
                  "importance": "high|medium|low"
                }
              ]
            }
          ]
        }
      ]
    }
  ],
  "warnings": ["Tableau de chaines de caractères : tout point flou ou hypothèse que l'IA a dû faire (responsable non nommé, date vague, etc)"],
  "confidence_score": 85
}

ATTENTION: Si un élément (livrable, jalon) n'est pas présent dans le texte pour une activité donnée, renvoie un tableau vide []. Ta seule et unique réponse doit être le JSON sérialisable et parseable, démarrant par { et terminant par }. N'ajoute pas de balises markdown ```json autour.
EOT;
    }
}
