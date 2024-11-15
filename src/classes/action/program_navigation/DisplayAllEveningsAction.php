<?php

namespace iutnc\nrv\action\program_navigation;


use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des soirée(titre, date, horaire, image)
 */
class DisplayAllEveningsAction extends Action
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
        $evenings = $repository->findAllEvenings();

        $boutonAjouter = "";
        if (Authz::checkRole(Authz::STAFF)) {
            $boutonAjouter = <<<HTML
            <a href="?action=add-evening" class="btn btn-primary m-5">Ajouter une soirée </a>
            HTML;
        }
        return ArrayRenderer::render($evenings, Renderer::COMPACT, true) . $boutonAjouter;
    }
}