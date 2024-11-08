<?php

namespace iutnc\nrv\action\program_navigation;
use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage du détail d’une soirée : nom de la soirée, thématique, date et horaire, lieu,
 * tarifs, ainsi que la liste des spectacles : titre, artistes, description, style de musique, vidéo
 */
class DisplayEveningDetailsAction extends Action
{
    // Utiliser findEveningDetails ET findShowsInEvening

    /**
     * @throws \Exception
     */
    public function executePost()
    {
        $repo = NrvRepository::getInstance();
        $id = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS); // on filtre l'id de la soirée récupéré dans l'url
        $showList = $repo->findShowsInEvening($id);

        $eveningDetails = $repo->findEveningDetails($id);

        return "";
    }

    public function executeGet()
    {
        return "";
    }
}