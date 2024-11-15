<?php

namespace iutnc\nrv\action\filter;


use DateTime;
use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des spectacles(titre, date, horaire, image)
 */
class DisplayShowsByDateAction extends Action
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
            return "Aucune date n'a été renseignée";
        }
        $_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $id = $_GET['id'];

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $id)) {
            return "La date n'est pas au bon format";
        }

        $date = new DateTime($id);

        $html = <<<HTML
                <div class="d-flex align-items-center justify-content-center my-4 px-4">
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                    <h1 class="text-center mx-3">RESULTATS DE LA RECHERCHE</h1>
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                </div>
                <p class="text-center">Résultat pour la date : {$id}</p>
HTML;
        try {
            $showsByDay = NrvRepository::getInstance()->findShowsByDate($date);
            $html .= ArrayRenderer::render($showsByDay, Renderer::COMPACT, true);
        } catch (Exception) {
            $html .= '<p class="text-center">Aucun résultats correspondants.</p>';
        }

        return $html;
    }
}
