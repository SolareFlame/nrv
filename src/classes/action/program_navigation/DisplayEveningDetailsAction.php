<?php

namespace iutnc\nrv\action\program_navigation;
use iutnc\nrv\action\Action;
use iutnc\nrv\object\Evening;
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
     * @throws \Exception
     */
    public function executePost(): string
    {
        return "";
    }

    public function executeGet(): string
    {

        $repo = NrvRepository::getInstance();
        $id = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS); // on filtre l'id de la soirée récupéré dans l'url
        $evening = $repo->findEveningDetails($id);
        try {
            $showList = $repo->findShowsInEvening($id);
        } catch (\Exception $e){
            $showList = [];
        }

        $evening->addShows($showList);
        $renderEvening =  new EveningRenderer($evening);

        return $renderEvening->render(Renderer::LONG);
    }
}