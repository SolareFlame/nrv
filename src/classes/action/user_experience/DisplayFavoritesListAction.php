<?php

namespace iutnc\nrv\action\user_experience;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affiche la liste de préférence (sans authentification ni création de
 * compte)
 */
class DisplayFavoritesListAction extends Action
{
    /**
     * @throws Exception
     */
    public function execute(): string
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];

        if (isset($_COOKIE['favorites'])) {
            $_SESSION['favorites'] = json_decode($_COOKIE['favorites'], true);
        }

        // verif si une liste est deja présente
        if (empty($_SESSION['favorites']))
            return "Aucun favoris";

        $FavShowList = NrvRepository::getInstance()->findShowsByListId($_SESSION['favorites']);
        return ArrayRenderer::render($FavShowList, Renderer::COMPACT, true);
    }

    public function executePost(): string
    {
        return "";
    }

    public function executeGet(): string
    {
        return "";
    }
}


