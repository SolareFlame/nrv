<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

class LoginAction extends Action
{
    /**
     * @inheritDoc
     */
    function executePost(): string
    {
        if (($rapport = $this->sanitize()) !== true)
            return $rapport;
        else {
            $ret = "";
            if (NrvRepository::getInstance()->login($_POST['password'])) {  // Si l'authentification est réussie
                header("Location: {$_SESSION["previous"]}");  // On revient à la page précédente
            } else {
                $ret .= $this->errorMessage("Les identifiants ne sont pas reconnus.");
            }
            return $ret;
        }
    }

    /**
     * @inheritDoc
     */
    function executeGet(): string
    {
        $user = AuthnProvider::getSignedInUser();
        if ($user['id'] != -1) {  // si l'utilisateur est déjà connecté et essaie de se reconnecter
            header("Location: index.php");
            return "";
        } else {
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
    }

    private
    function sanitize(): string|bool
    {
        if (empty($_POST['password'])) {
            $this->errorMessage("Tous les champs sont obligatoires.");
        }

        // Même si cela est parfois non recommandé, je filtre tout de même le mot de passe
        // Et puis dans le meilleur des cas, cela crée une sécurité supplémentaire car me mdp est deja modifié avant le hashage
        // Et dans le meilleur des cas, je me protège contre toutes sortes d'attaques possibles et inimaginables
        $_POST['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);

        return true;
    }

    protected function errorMessage(string $message): string
    {
        $errorMessage = <<<HTML
        <div class="alert alert-danger mt-3 text-center" role="alert">
             $message
        </div>
        HTML;

        $res = $this->executeGet();
        return str_replace(
            '<button class="btn btn-outline-warning me-2 w-100 py-2" type="submit" >Se connecter</button>',
            '<button class="btn btn-outline-warning me-2 w-100 py-2" type="submit" >Se connecter</button><br>' . $errorMessage,
            $res
        );
    }
}






