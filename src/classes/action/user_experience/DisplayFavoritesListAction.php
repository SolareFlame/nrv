<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\object\Show;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\render\ShowRenderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affiche la liste de préférence (sans authentification ni création de
 * compte)
 */
class DisplayFavoritesListAction extends Action
{
    /**
     * @throws \Exception
     */
    public function execute(): string
    {
        // verif si une liste est deja présente
        if (empty($_SESSION['favorites']))
            return "Aucun favoris";

        $FavShowList = NrvRepository::getInstance()->getShowsByListId($_SESSION['favorites']);

        $res = "";
        foreach ($FavShowList as $show) {
            /*var_dump($showstr);
            $show = new Show($showstr['url'], $showstr['style'], (int)$showstr['duration'], $showstr['startDate'],
                $showstr['description'], $showstr['title'], $showstr['id']);*/

            $sr = new ShowRenderer(unserialize($show));
            $res .= $sr->render(Renderer::LONG);
        }

        return $res;
    }

    public function executePost()
    {
    }

    public function executeGet()
    {
    }
}


