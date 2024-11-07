<?php

namespace iutnc\nrv\dispatch;

use Exception;
use iutnc\nrv\action\DefaultAction;
use iutnc\nrv\action\user_experience\LoginAction;
use iutnc\nrv\action\user_experience\LogoutAction;

class Dispatcher
{
    protected string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    /**
     * Fonction qui exécute l'action demandée
     * @throws Exception
     */
    public function run(): void
    {
        if (($_SERVER['REQUEST_METHOD'] !== "POST") && ($_SERVER['REQUEST_METHOD'] !== "GET"))
            $this->renderPage("Erreur 418 : I'm a teapot");  // Un peu d'humour pour celui qui s'amuserait à envoyer une requête autre que POST ou GET
        else {
            switch ($this->action) {
                case 'default':
                    $act = new DefaultAction();
                    break;
                case 'login':
                    $act = new LoginAction();
                    break;
                case 'logout':
                    $act = new LogoutAction();
                    break;
                default:
                    $this->renderPage("Action inconnue");
                    break;
            }
            if (isset($act))
                $this->renderPage($act->execute());
        }
    }


    /**
     * Fonction qui affiche la page HTML
     * @param string $html le code HTML à afficher
     * @throws Exception
     */
    private function renderPage(string $html): void
    {
        //DeefyRepository::getInstance()->verifToken();
        //$user = AuthnProvider::getSignedInUser();
        //$logInOrOut = $user['id'] == -1 ? "<a href='?action=login'>Connexion</a>" : "<a href='?action=logout'>Déconnexion</a>";

        $ret = <<<HTML
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRV</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="favicon.png" type="image/png">

</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">LOGO</a>        
        </div>
        <nav>
            <p>NAV</p>
        </nav>
    </header>
    <main>
        <div class="content">
        
            $html

        </div>
    </main>
    <footer>
        <a href="index.php?action=login">Vous etes orga ?</a>
        <a href="index.php?action=logout">Se deconnecter</a>
    </footer>
</body>
</html>
HTML;
        echo $ret;
    }
}

