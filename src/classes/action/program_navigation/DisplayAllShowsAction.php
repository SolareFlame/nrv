<?php

namespace iutnc\nrv\action\program_navigation;


use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des spectacles(titre, date, horaire, image)
 */
class DisplayAllShowsAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executeGet(): string
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];
        $repository = NrvRepository::getInstance();
        $shows = $repository->findAllShows();
        $user = AuthnProvider::getSignedInUser();
        $boutonAjouter = "";
        if ($user["role"] >= Authz::STAFF) {
            $boutonAjouter = <<<HTML
            <a href="?action=add-show" class="btn btn-primary m-5">Ajouter un Spectacle</a>
            HTML;
        }
        return ArrayRenderer::render($shows, Renderer::COMPACT, true) . $boutonAjouter;
    }
}