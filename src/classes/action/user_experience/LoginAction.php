<?php

namespace iutnc\nrv\action\user_experience;

use Exception;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\action\Action ;

class LoginAction extends Action {


    /**
     * @inheritDoc
     */
    function executePost(): string
    {

//        $username = filter_var($_POST["username"], FILTER_SANITIZE_EMAIL);
//        $password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
//
//        try {
//            NrvAuthnProvider::login($password);
//            header('Location: index.php');
//        } catch (AuthnException $e){
//            return $this->errorMessage($e->getMessage());
//        }


        $ret = "";
        try {
            NrvAuthnProvider::login($_POST['password']);
            header("Location: {$_SESSION["previous"]}");
        } catch (Exception $e) {
            $ret .= '<p style="color: red;">Les identifiants ne sont pas reconnus.</p>';
            $ret .= '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        return $ret ;
    }

    /**
     * @inheritDoc
     */
    function executeGet(): string
    {
        return <<<HTML
<form method="post" class="form-container">
    <h3 class="form-title text-warning">Connexion</h3>
        
    <div class="form-group">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
        </div>
    </div>
    
    <button class="btn btn-outline-warning me-2 w-100 py-2" type="submit" >Se connecter</button>
</form>
HTML;

    }

    protected function errorMessage(string $message) : string
    {
        $errorMessage = <<<HTML
        <div class="alert alert-danger mt-3 text-center" role="alert">
             $message
        </div>
HTML;
        $res = $this->executeGet();
        return str_replace(
            '<button class="btn btn-primary w-100 py-2" type="submit" >Se connecter</button>',
            '<button class="btn btn-primary w-100 py-2" type="submit" >Se connecter</button><br>' . $errorMessage,
            $res
        );
    }
}



