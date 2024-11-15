<?php

namespace iutnc\nrv\action\show_details;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\render\EveningRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage du détail d’une soirée : nom de la soirée, thématique, date et horaire, lieu,
 * tarifs, ainsi que la liste des spectacles : titre, artistes, description, style de musique, vidéo
 */
class DisplayEveningDetailsAction extends Action
{
    // Utiliser findEveningDetails ET findShowsInEvening

    /**
     * @throws Exception
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @throws Exception
     */
    public function executeGet(): string
    {

        $repo = NrvRepository::getInstance();
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS); // on filtre l'id de la soirée récupéré dans l'url
        $evening = $repo->findEveningDetails($id);
        try {
            $showList = $repo->findShowsInEvening($id);
        } catch (Exception) {
            $showList = [];
        }

        $evening->addShows($showList);
        $renderEvening = new EveningRenderer($evening);

        if (Authz::checkRole(Authz::STAFF)) {
            $boutonAjouter = <<<HTML
            <a href="?action=addShow2evening&id={$id}" class="btn btn-primary m-5">Ajouter un spectable</a>
            HTML;
        } else {
            $boutonAjouter = "";
        }

        return $renderEvening->render(Renderer::LONG) . $boutonAjouter;
    }
}