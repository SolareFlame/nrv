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
        if (!empty($_GET['id']))
            $idFav = $_GET['id'];
        else
            return "Veuillez fournir un identifiant de spectacle";

        if (isset($_COOKIE['favorites'])) {
            $_SESSION['favorites'] = json_decode($_COOKIE['favorites'], true);
        }

        // verif si une liste est deja présente, sinon on la crée
        if (empty($_SESSION['favorites']))
            $_SESSION['favorites'] = [];

        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$idFav]);

        setcookie('favorites', json_encode($_SESSION['favorites']), time() + (30 * 24 * 60 * 60), '/');

        header("Location: {$_SESSION["previous"]}");
        // FAUX STRING, METTRE UN HEADER POUR REMETTRE SUR LA MEME PAGE
        return "Supprimé de la liste de favoris";
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


