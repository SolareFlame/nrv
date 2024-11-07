<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affiche la liste de préférence (sans authentification ni création de
 * compte)
 */
class DisplayFavoritesListAction extends Action
{
    public function execute(): string
    {
        // verif si une liste est deja présente
        if (empty($_SESSION['favorites']))
            return "Aucun favoris";
        echo "0" . var_dump($_SESSION['favorites']);
        $FavShowList = NrvRepository::getInstance()->getShowsByListId($_SESSION['favorites']);
        echo "1" . var_dump($FavShowList);
        $res = "";
        foreach ($FavShowList as $show) {
            $res .= $show->title . " - " . $show->DisplayArtiste() . " - " . $show->description .
                "<br>à " . $show->startDate . " pendant " . $show->duration . "<br>" .
            "<a href='index.php?action=evening&showId=" . $show->id . "'>Voir le spectacle</a><br>" .
             " $show->url $show->style<br><br><br> ";
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


