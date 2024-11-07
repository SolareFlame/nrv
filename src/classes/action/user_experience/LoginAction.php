<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\repository\NrvRepository;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\action\Action ;

class LoginAction extends Action {


    /**
     * @inheritDoc
     */
    function executePost()
    {
        $ret = "";
        try {
            NrvAuthnProvider::login($_POST['pwd']);
            $ret .= '<p>Vous êtes connecté avec le token ' . htmlspecialchars($_SESSION['pwd']) . '</p>';
        } catch (\Exception $e) {
            $ret .= '<p style="color: red;">Les identifiants ne sont pas reconnus.</p>';
            $ret .= '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        return $ret ;
    }

    /**
     * @inheritDoc
     */
    function executeGet()
    {
        $ret = '<form action="index.php?action=login" method="POST">
            <br><strong style="font-size: 30px;">Se connecter</strong><br><br>

            <label for="pwd" style="font-size: 25px;">Mot de passe: </label>
            <input type="password" id="pwd" name="pwd" required><br><br>

            <button type="submit" style="font-size: 25px;">Se connecter !</button>
        </form>';
        return $ret ;
    }
}



