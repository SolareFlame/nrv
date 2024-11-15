<?php

namespace iutnc\nrv\action\filter;


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
class DisplayShowsByLocationAction extends Action
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
        if (empty($_GET['id'])) {
            return "Aucun lieu n'a été renseigné";
        }
        $_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id = $_GET['id'];

        $loc = NrvRepository::getInstance()->findLocationById($id);

        $html = <<<HTML
                <div class="d-flex align-items-center justify-content-center my-4 px-4">
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                    <h1 class="text-center mx-3">RESULTATS DE LA RECHERCHE</h1>
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                </div>
                <p class="text-center">Résultat pour le lieu : {$loc->name}</p>
HTML;
        try {
            $showsByLocation = NrvRepository::getInstance()->findShowsByLocation($id);
            $html .= ArrayRenderer::render($showsByLocation, Renderer::COMPACT, true);
        } catch (Exception) {
            $html .= '<p class="text-center">Aucun résultats correspondants.</p>';
        }

        return $html;
    }
}
