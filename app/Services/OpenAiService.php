<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $defaultModel;

    public function __construct()
    {
        $this->apiKey       = config('services.openai.key', '');
        $this->baseUrl      = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->defaultModel = config('services.openai.model', 'gpt-4o');
    }

    /**
     * Appelle l'API OpenAI pour générer une structure JSON.
     *
     * Retourne :
     *   ['success' => true,  'structured_data' => [...], 'tokens_used' => int, ...]
     *   ['success' => false, 'error_message' => string, 'error_code' => int|null]
     *   ['success' => true,  'is_mock' => true, ...] — uniquement si clé absente (mode démo)
     */
    public function generateJsonAnalysis(string $systemPrompt, string $userText, int $maxTokens = 4000): array
    {
        // Mode démo : aucune clé configurée → simulation acceptée
        if (empty($this->apiKey)) {
            Log::warning('OpenAI : clé API absente — mode simulation activé.');
            return $this->getMockResponse('Clé API non configurée');
        }

        $payload = [
            'model'           => $this->defaultModel,
            'messages'        => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userText],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens'      => $maxTokens,
            'temperature'     => 0.2,
        ];

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(120)
                // Retry uniquement sur les erreurs réseau passagères, PAS sur 429/401/400
                ->retry(2, 3000, function (\Exception $e, $response) {
                    // Ne pas retenter sur des erreurs clientes (4xx)
                    if ($response && $response->status() >= 400 && $response->status() < 500) {
                        return false;
                    }
                    return true;
                }, throw: false)
                ->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->failed()) {
                $status = $response->status();
                $body   = $response->json();

                Log::error('OpenAI API — réponse HTTP en échec', [
                    'status' => $status,
                    'error'  => data_get($body, 'error.message'),
                ]);

                // 429 : rate-limit ou quota dépassé — erreur claire, pas de mock
                if ($status === 429) {
                    $retryAfter = $response->header('Retry-After');
                    $msg = 'Le quota OpenAI est dépassé ou le débit est trop élevé (429).';
                    if ($retryAfter) {
                        $msg .= " Réessayez dans {$retryAfter} secondes.";
                    }
                    return ['success' => false, 'error_message' => $msg, 'error_code' => 429];
                }

                // 401 : clé invalide
                if ($status === 401) {
                    return [
                        'success'       => false,
                        'error_message' => 'Clé API OpenAI invalide ou révoquée (401). Vérifiez OPENAI_API_KEY dans le fichier .env.',
                        'error_code'    => 401,
                    ];
                }

                // 503 : OpenAI indisponible → simulation acceptable
                if ($status === 503) {
                    Log::warning('OpenAI 503 — bascule simulation.');
                    return $this->getMockResponse("OpenAI temporairement indisponible (503)");
                }

                return [
                    'success'       => false,
                    'error_message' => "Erreur de l'API OpenAI (code {$status}) : " . data_get($body, 'error.message', 'Erreur inconnue'),
                    'error_code'    => $status,
                ];
            }

            $data = $response->json();

            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('OpenAI — format de réponse inattendu', ['data' => $data]);
                return ['success' => false, 'error_message' => 'La réponse de l\'IA ne contient pas le format JSON attendu.', 'error_code' => null];
            }

            $jsonContent = $data['choices'][0]['message']['content'];
            $parsedJson  = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OpenAI — JSON invalide reçu', ['content' => substr($jsonContent, 0, 500)]);
                return ['success' => false, 'error_message' => 'L\'IA n\'a pas renvoyé un JSON valide. Relancez l\'analyse.', 'error_code' => null];
            }

            return [
                'success'         => true,
                'is_mock'         => false,
                'structured_data' => $parsedJson,
                'raw_response'    => $data,
                'tokens_used'     => $data['usage']['total_tokens'] ?? 0,
            ];

        } catch (RequestException $e) {
            // Exception HTTP Laravel (après les retries épuisés sur 5xx)
            $status = $e->response?->status();
            Log::error('OpenAI RequestException', ['status' => $status, 'msg' => $e->getMessage()]);

            if ($status === 429) {
                return ['success' => false, 'error_message' => 'Quota OpenAI dépassé (429). Attendez quelques instants et relancez l\'analyse.', 'error_code' => 429];
            }

            // Timeout ou connexion refusée → simulation acceptable
            return $this->getMockResponse('Erreur réseau : ' . $e->getMessage());

        } catch (\Exception $e) {
            // Timeout, connexion refusée, etc. → simulation acceptable pour la robustesse
            Log::error('OpenAI Exception générale', ['msg' => $e->getMessage()]);

            if (str_contains($e->getMessage(), '429') || str_contains($e->getMessage(), 'rate limit')) {
                return ['success' => false, 'error_message' => 'Quota OpenAI dépassé. Attendez quelques instants et relancez l\'analyse.', 'error_code' => 429];
            }

            return $this->getMockResponse('Exception réseau : ' . class_basename($e));
        }
    }

    /**
     * Simulation locale — uniquement quand la clé est absente ou OpenAI est hors ligne (503).
     */
    private function getMockResponse(string $note = 'Clé API manquante'): array
    {
        $safeNote = htmlspecialchars($note, ENT_QUOTES);

        $data = [
            'document_type'      => 'décision',
            'document_title'     => 'Création du Conseil de Paix et de Sécurité (SIMULATION)',
            'document_reference' => 'TEST/CEEAC/2026',
            'summary'            => "Ceci est une analyse générée en mode simulation. Motif : {$safeNote}",
            'axes_strategiques'  => [
                [
                    'title'           => 'Axe 1 : Mise en place opérationnelle',
                    'description'     => 'Validation et lancement des directives organiques extraites.',
                    'priority'        => 'high',
                    'actions_metiers' => [
                        [
                            'title'               => 'Déploiement initial',
                            'description'         => 'Mobilisation des équipes et affectations budgétaires.',
                            'responsable_presume' => 'Secrétariat Général',
                            'activites'           => [
                                [
                                    'title'          => 'Évaluation préliminaire',
                                    'description'    => 'Tournée d\'inspection des structures.',
                                    'acteur_presume' => 'Experts techniques',
                                    'duree_estimee'  => '2 mois',
                                    'livrables'      => [
                                        [
                                            'title'           => 'Rapport d\'évaluation',
                                            'description'     => 'Synthèse consolidée des besoins.',
                                            'type'            => 'rapport',
                                            'preuve_attendue' => 'Document validé par le conseil',
                                        ],
                                    ],
                                    'jalons' => [
                                        [
                                            'title'             => 'Fin de l\'évaluation',
                                            'description'       => 'Réunion de clôture.',
                                            'echeance_estimee'  => 'Trimestre 2',
                                            'importance'        => 'high',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'warnings'         => [
                'Données de simulation — API OpenAI non jointe.',
                "Motif : {$safeNote}",
            ],
            'confidence_score' => 0,
        ];

        return [
            'success'         => true,
            'is_mock'         => true,
            'structured_data' => $data,
            'raw_response'    => ['mock' => true, 'note' => $note],
            'tokens_used'     => 0,
        ];
    }
}
