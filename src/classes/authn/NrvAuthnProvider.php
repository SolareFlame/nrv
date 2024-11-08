<?php

namespace iutnc\nrv\authn;

use iutnc\nrv\exception\AuthnException;
use iutnc\nrv\repository\NrvRepository;

class NrvAuthnProvider {

    public static function login(string $passwd2check){

        $r = NrvRepository::getInstance();

        $isFound = $r->authentificateUser($passwd2check) ;

        if($isFound){
            $_SESSION['pwd'] = $passwd2check ;
        } else {
            throw new AuthnException("Identifiant non reconnu");
            
        }
    }

    public static function register(string $email, string $password, string $password_confirmation){

        // $r = NrvRepository::getInstance();

        // try {
        // 	$r->getUserByEmail($email) ;
        // } catch(\Exception $e){


	    //     if (! filter_var($email, FILTER_VALIDATE_EMAIL))
	    //         throw new AuthnException("Le mail est incorrect");

	    //     if (strlen($password) < 10)
	    //         throw new AuthnException("Le mot de passe est trop court");

	    //     if (! preg_match('/[A-Z]/', $password))
	    //         throw new AuthnException("Le mot de passe doit contenir une majuscule");

	    //     if ($password != $password_confirmation)
	    //     	throw new AuthnException("Les 2 mdp doivent correspondre") ;

	    //     $hash = password_hash($password, PASSWORD_DEFAULT, ['cost'=>12]);
	    //     $r->addUser($email, $hash, 1);

	    //     return ;
	    // }

	    // throw new AuthnException("Le mail est deja utilisé");
    }

    public static function logout(){
        session_destroy();
    }

    public static function asPermission($permissionLevel): bool {
        
        if(!isset($_SESSION['pwd'])){
            return false ;
        }

        // etc
    }
}