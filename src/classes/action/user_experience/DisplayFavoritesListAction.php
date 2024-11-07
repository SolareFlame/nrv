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

        $FavShowList = NrvRepository::getInstance()->getShowsByListId($_SESSION['favorites']);

        $res = "";
        foreach ($FavShowList as $show) {
            $res .= $show->title . " - " . $show->getArtist() . " - " . $show->getPrice() . "€<br>";
        }


        // FAUX STRING, METTRE UN HEADER POUR REMETTRE SUR LA MEME PAGE
        return "Ajouté à la liste de favoris";
    }

    public function executePost()
    {
    }

    public function executeGet()
    {
    }
}


