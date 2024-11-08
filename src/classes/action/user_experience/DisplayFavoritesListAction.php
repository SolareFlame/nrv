<?php

namespace iutnc\nrv\action\user_experience;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\object\Show;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\Render\Renderer;
use iutnc\nrv\Render\ShowRenderer;
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
        // verif si une liste est deja présente
        if (empty($_SESSION['favorites']))
            return "Aucun favoris";

        $FavShowList = NrvRepository::getInstance()->findShowsByListId($_SESSION['favorites']);

        return ArrayRenderer::render($FavShowList, Renderer::LONG, true);
        /*$res = "";
        foreach ($FavShowList as $show) {
            $sr = new ShowRenderer(unserialize($show));
            $res .= $sr->render(Renderer::LONG);
        }

        return $res;*/
    }

    public function executePost()
    {
    }

    public function executeGet()
    {
    }
}


