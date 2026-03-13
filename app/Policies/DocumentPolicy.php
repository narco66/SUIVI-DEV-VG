<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /** Lister / accéder au module GED. */
    public function viewAny(User $user): bool
    {
        return $user->can('ged.view')
            || $user->can('documents.view')
            || $user->hasAnyRole([
                'super_admin', 'admin_technique', 'admin_metier',
                'validateur', 'point_focal', 'point_focal_etat_membre',
                'utilisateur_consultation',
            ]);
    }

    /**
     * Voir un document individuel, en tenant compte du niveau de confidentialité.
     * rank <= 2 = public / interne ; rank >= 3 = confidentiel / restreint.
     */
    public function view(User $user, Document $document): bool
    {
        // Admins et déposant : accès complet
        if (
            $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier'])
            || $user->can('documents.view')
            || $document->uploader_id === $user->id
        ) {
            return true;
        }

        // Autres rôles : accès aux documents non confidentiels (rank <= 2)
        if ($user->hasAnyRole([
            'validateur', 'point_focal', 'point_focal_etat_membre', 'utilisateur_consultation',
        ])) {
            $level = $document->confidentialityLevel;
            if (!$level || $level->rank <= 2) {
                return true;
            }
        }

        return false;
    }

    /** Déposer un nouveau document. */
    public function create(User $user): bool
    {
        return $user->can('documents.upload')
            || $user->hasAnyRole([
                'super_admin', 'admin_technique', 'admin_metier',
                'point_focal', 'point_focal_etat_membre',
            ]);
    }

    /** Mettre à jour les métadonnées ou remplacer un fichier (nouvelle version). */
    public function update(User $user, Document $document): bool
    {
        return $user->can('documents.update')
            || $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier'])
            || $document->uploader_id === $user->id;
    }

    /** Supprimer un document (suppression logique). */
    public function delete(User $user, Document $document): bool
    {
        return $user->can('documents.delete')
            || $user->hasAnyRole(['super_admin', 'admin_technique']);
    }

    /** Télécharger un fichier. */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    /** Lancer un workflow de validation sur un document. */
    public function startValidation(User $user, Document $document): bool
    {
        return $user->can('documents.validate')
            || $user->hasAnyRole(['super_admin', 'admin_metier', 'admin_technique']);
    }
}
