<?php

namespace iutnc\nrv\auth;

/**
 * La classe AuthnProvider qui permet de gérer l’authentification.
 * Elle fournit une méthode getSignedInUser() qui retourne l’utilisateur stocké en session.
 */
class AuthnProvider
{
    /**
     * La méthode getSignedInUser() qui retourne l’utilisateur stocké en session.
     * Si aucun utilisateur n’est authentifié, elle déclenche une exception.
     *
     * @return array Les infos utilisateurs stockées en session
     */
    public static function getSignedInUser(): array
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['user'] = [
                'id' => -1,
                'role' => Authz::NO_USER
            ];
            return $_SESSION['user'];
        }
        return $_SESSION['user'];
    }

}