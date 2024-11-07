<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

/**
 * Ajouter un spectacle dans sa liste de préférence (sans authentification ni création de
 * compte)
 */
class AddShowToFavoritesAction extends Action
{
    public function execute(): string
    {
        // verif si l'id est bien fourni
        if (!empty($_GET['addFavId'])) {
            $idFav = $_GET['addFavId'];
        } else if (!empty($_POST['addFavId'])) {
            $idFav = $_POST['addFavId'];
        } else {
            return "Veuillez fournir un identifiant de spectacle";
        }

        // verif si une liste est deja présente, sinon on la crée
        if (empty($_SESSION['favorites']))
            $_SESSION['favorites'] = [];

        // verif si le show existe
        if (!NrvRepository::getInstance()->VerifIdFav($idFav)) {
            return "L'identifiant de spectacle n'existe pas";
        }

        if (!in_array($idFav, $_SESSION['favorites'])) {
            array_push($_SESSION['favorites'], $idFav);
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


