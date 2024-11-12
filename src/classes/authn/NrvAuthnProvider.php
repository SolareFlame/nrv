<?php

namespace iutnc\nrv\authn;

use iutnc\deefy\exception\AuthException;
use iutnc\nrv\exception\AuthnException;
use iutnc\nrv\object\User;
use iutnc\nrv\repository\NrvRepository;
use Ramsey\Uuid\Uuid;


/**
 * Gere les authentification et autorisation
 */
class NrvAuthnProvider {

    /**
     * @throws AuthnException
     */
    public static function login(string $passwd2check){


//        $repo = NrvRepository::getInstance();
//        $user = $repo->getUser($username);
//
//        if (!password_verify($passwd2check, $user->password)) {
//            throw new AuthnException("Impossible de se connecter : nom d'utilisateur ou mot de passe incorrect.");
//        }
//        $_SESSION['user'] = serialize($user);

        $r = NrvRepository::getInstance();
        $uuid = $r->authentificateUser($passwd2check) ;

        if($uuid!=null){
            $_SESSION['pwd'] = $passwd2check ;
            $_SESSION['id'] = $uuid ;
        } else {
            throw new AuthnException("Identifiant non reconnu");
        }
    }

    /**
     * Enregistre un nouvel utilisateur.
     * @param string $password Le mot de passe de l'utilisateur
     * @throws AuthnException Si l'email est invalide ou si le mot de passe ne respecte pas les critères de sécurité.
     * @throws \Exception
     */
    public static function register($password,$permission): void
    {

        $r = NrvRepository::getInstance();
        $uuid = Uuid::uuid4();
        if (self::checkPasswordStrength($password, 10)) {
            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost'=>12]);
            $user = new User($uuid,$permission,$hash);
            $r->createAccount($user);
        } else{
            throw new AuthnException("Le mot de passe doit contenir au moins 10 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }




    }

    public static function logout(){
        session_destroy();
        header('Location: index.php');
    }


    public static function asPermission($permissionLevel): bool {
        
        if(!isset($_SESSION['pwd']) || !isset($_SESSION['id'])){  // l'user n'est pas connecté
            return false ;
        }

        $r = NrvRepository::getInstance() ;

        if($r->authentificateUser($_SESSION['pwd']) != $_SESSION['id']){  // l'id et le password ne vont pas ensemble
            return false ;
        }

        return $r->checkRole($_SESSION['id'],$permissionLevel) ;
    }

    /**
     * Vérifie la force du mot de passe.
     *
     * @param string $pass Le mot de passe à vérifier.
     * @param int $minimumLength La longueur minimale du mot de passe (par défaut 8).
     * @return bool True si le mot de passe est fort, sinon false.
     */
    public static function checkPasswordStrength(string $pass, int $minimumLength = 8): bool
    {
        $length = (strlen($pass) >= $minimumLength); // La longueur doit être suffisante
        $digit = preg_match("#[\d]#", $pass); // Au moins un chiffre
        $special = preg_match("#[\W]#", $pass); // Au moins un caractère spécial
        $lower = preg_match("#[a-z]#", $pass); // Au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // Au moins une majuscule

        // Tous les critères doivent être vrais
        return $length && $digit && $special && $lower && $upper;
    }

    /**
     * Obtient l'utilisateur actuellement connecté.
     *
     * @return User L'utilisateur connecté.
     * @throws AuthnException Si aucun utilisateur n'est connecté.
     */
    public static function getSignedInUser (): User
    {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Vous n'avez pas l'autorisation d'accéder à cette fonctionnalité.");
        }

        return unserialize($_SESSION['user']);
    }
}