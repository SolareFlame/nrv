<?php

namespace iutnc\nrv\action\program_navigation;

use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des spectacles(titre, date, horaire, image)
 */
class DisplayAllShowsAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost()
    {
        return "";
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function executeGet()
    {
        $repository = NrvRepository::getInstance();
        $shows = $repository->findAllShows();
        $render = new ListRender($shows);
        return $render->render();
    }
}