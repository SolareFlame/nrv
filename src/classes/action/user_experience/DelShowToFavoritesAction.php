<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;

/**
 * Supprimer un spectacle dans sa liste de préférence (sans authentification ni création de
 * compte)
 */
class DelShowToFavoritesAction extends Action
{
    public function execute(): string
    {
        // verif si l'id est bien fourni
        if (empty($_GET['addFavId'])) {
            $idFav = $_GET['addFavId'];
        } else if (empty($_POST['addFavId'])) {
            $idFav = $_POST['addFavId'];
        } else {
            return "Veuillez fournir un identifiant de spectacle";
        }

        // verif si une liste est deja présente, sinon on la crée
        if (empty($_SESSION['favorites']))
            $_SESSION['favorites'] = [];

        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], $idFav);

        // FAUX STRING, METTRE UN HEADER POUR REMETTRE SUR LA MEME PAGE
        return "Supprimé de la liste de favoris";
    }

    public function executePost()
    {
    }

    public function executeGet()
    {
    }
}


