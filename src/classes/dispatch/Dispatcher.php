<?php

namespace iutnc\nrv\dispatch;

use Exception;
use iutnc\nrv\action\DefaultAction;
use iutnc\nrv\action\filter\FilterByLocation;
use iutnc\nrv\action\filter\FilterByStyle;
use iutnc\nrv\action\program_management\CancelEveningAction;
use iutnc\nrv\action\program_management\CancelShowAction;
use iutnc\nrv\action\program_management\CreateEveningAction;
use iutnc\nrv\action\program_management\CreateShowAction;
use iutnc\nrv\action\program_management\CreateStaffAccountAction;
use iutnc\nrv\action\program_management\EditShowAction;
use iutnc\nrv\action\program_management\EditShowsInEveningAction;
use iutnc\nrv\action\program_navigation\DisplayAllShowsAction;
use iutnc\nrv\action\program_navigation\DisplayEveningDetailsAction;
use iutnc\nrv\action\program_navigation\DisplayShowsByLocationAction;
use iutnc\nrv\action\program_navigation\DisplayShowsByStyleAction;
use iutnc\nrv\action\program_navigation\DisplayAllEveningsAction;
use iutnc\nrv\action\show_details\DisplayShowDetailsAction;
use iutnc\nrv\action\show_details\DisplayShowsByDayAction;
use iutnc\nrv\action\user_experience\AddShowToEveningAction;
use iutnc\nrv\action\user_experience\AddShowToFavoritesAction;
use iutnc\nrv\action\user_experience\ContactAction;
use iutnc\nrv\action\user_experience\DelShowToFavoritesAction;
use iutnc\nrv\action\user_experience\DisplayFavoritesListAction;
use iutnc\nrv\action\user_experience\LoginAction;
use iutnc\nrv\action\user_experience\LogoutAction;
use iutnc\nrv\action\filter\FilterByDate;


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
                case 'shows':
                    $act = new DisplayAllShowsAction();
                    break;
                case 'evenings':
                    $act = new DisplayAllEveningsAction();
                    break;
                case 'evening':
                    $act = new DisplayEveningDetailsAction();
                    break;
                case 'showByStyle':
                    $act = new DisplayShowsByStyleAction();
                    break;
                case 'showByLocation':
                    $act = new DisplayShowsByLocationAction();
                    break;
                case 'showByDay':
                    $act = new DisplayShowsByDayAction();
                    break;
                case 'showDetails':
                    $act = new DisplayShowDetailsAction();
                    break;
                case 'add-evening':
                    $act = new CreateEveningAction();
                    break;
                case 'add-staff':
                    $act = new CreateStaffAccountAction();
                    break;
                case 'edit-show':
                    $act = new EditShowAction();
                    break;
                case 'edit-evening':
                    $act = new EditShowsInEveningAction();
                    break;
                case 'addShow2Fav':
                    $act = new AddShowToFavoritesAction();
                    break;
                case 'delShow2fav':
                    $act = new DelShowToFavoritesAction();
                    break;
                case 'cancel-show':
                    $act = new CancelShowAction();
                    break;
                case 'cancel-evening':
                    $act = new CancelEveningAction();
                    break;
                case 'add-show':
                    $act = new CreateShowAction();
                    break;
                case 'addShow2evening':
                    $act = new AddShowToEveningAction();
                    break;
                case 'favs':
                    $act = new DisplayFavoritesListAction();
                    break;
                case 'contact':
                    $act = new ContactAction();
                    break;
                case 'filterByDate':
                    $act = new FilterByDate();
                    break;
                case 'filterByLocation':
                    $act = new FilterByLocation();
                    break;
                case 'filterBySytle':
                    $act = new FilterByStyle();
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
        ob_start();
        include("src/html/home.php");

        $content = ob_get_clean();
        $page = str_replace("{{CONTENT}}", $html, $content);

        echo $page;
    }

}