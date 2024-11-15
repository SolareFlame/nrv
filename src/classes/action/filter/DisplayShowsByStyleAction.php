<?php

namespace iutnc\nrv\action\filter;


use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des spectacles(titre, date, horaire, image)
 */
class DisplayShowsByStyleAction extends Action
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
            return "Aucun style n'a été renseigné";
        }
        $_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id = $_GET['id'];

        $style = NrvRepository::getInstance()->findStyleById($id);

        $html = <<<HTML
                <div class="d-flex align-items-center justify-content-center my-4 px-4">
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                    <h1 class="text-center mx-3">RESULTATS DE LA RECHERCHE</h1>
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                </div>
                <p class="text-center">Résultat pour le style : {$style}</p>
HTML;
        try {
            $showsByStyle = NrvRepository::getInstance()->findShowsByStyle($id);
            $html .= ArrayRenderer::render($showsByStyle, Renderer::COMPACT, true);
        } catch (Exception) {
            $html .= '<p class="text-center">Aucun résultats correspondants.</p>';
        }

        return $html;
    }
}
