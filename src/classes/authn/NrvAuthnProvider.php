<?php

namespace iutnc\nrv\authn;

use iutnc\nrv\exception\AuthnException;
use iutnc\nrv\repository\NrvRepository;
use Ramsey\Uuid\Uuid;


/**
 * Gere les authentification et autorisation
 */
class NrvAuthnProvider {

    public static function login(string $passwd2check){

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
     * $password : le password en clair de l'user
     */
    public static function register($password,$permission){

        $r = NrvRepository::getInstance();
        $uuid = Uuid::uuid4();

	    $hash = password_hash($password, PASSWORD_DEFAULT, ['cost'=>12]);

        $u = new User($uuid,$permission,$hash) ;
	    $r->createAccount($u);
    }

    public static function logout(){
        session_destroy();
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
}