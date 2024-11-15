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
     * ATTENTION : Cette fonction retourne 403 si l’utilisateur n’a pas le rôle attendu.
     * Elle est donc a utilisé pour les actions qui sont INTERDITE à certains rôles.
     * Si vous voulez juste vérifier le role sans forbidden access adressé vous a AuthnProvider::getSignedInUser()
     * PS: ça ne le fais plus, on test une alternative
     *
     * @param int $required le rôle minimum attendu
     * @return bool true si l’utilisateur a le rôle attendu, false sinon
     */
    public static function checkRole(int $required): bool
    {
        $user = AuthnProvider::getSignedInUser();
        if ($user['role'] < $required) {
            //http_response_code(403);
            return false;
        }
        return true;
    }

}