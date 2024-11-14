<?php

namespace iutnc\nrv\auth;

use iutnc\nrv\exception\AccessControlException;

/**
 * La classe Authz qui permet de gérer l’autorisation.
 * Elle fournit des méthodes pour vérifier les droits d’accès.
 * Elle fournit des constantes pour les rôles.
 * Elle reçoit un tableau d’informations utilisateur en paramètre.
 */
class Authz
{
    /**
     * Constantes pour les rôles
     */
    public const ADMIN = 100;
    public const STAFF = 50;
    public const NO_USER = 0;

    /**
     * La méthode checkRole() qui reçoit un rôle attendu et vérifie que le rôle de l’utilisateur
     * authentifié est conforme.
     *
     * @param int $required
     */
    public static function checkRole(int $required): bool
    {
        $user = AuthnProvider::getSignedInUser();
        if ($user->role < $required) {
            http_response_code(403);
            return false;
        }
        return true;
    }

}