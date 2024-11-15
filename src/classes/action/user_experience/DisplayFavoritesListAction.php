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
            return $this->renderEmptyFavorites();

        $FavShowList = NrvRepository::getInstance()->findShowsByListId($_SESSION['favorites']);
        return ArrayRenderer::render($FavShowList, Renderer::COMPACT, true);
    }

    private function renderEmptyFavorites(): string
    {
        $html = "<div style='max-width: 800px; margin: 50px auto; padding: 20px; background-color: #f8d7da; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
        $html .= "<h2 style='color: #721c24; text-align: center;'>Aucun favori trouvé</h2>";
        $html .= "<p style='font-size: 1.2em; color: #721c24; text-align: center;'>Vous n'avez encore ajouté aucun titre à votre liste de favoris.</p>";
        $html .= "<p style='font-size: 1.1em; color: #666; text-align: center;'>Essayez d'ajouter un ou plusieurs titres à vos favoris pour les retrouver ici.</p>";
        $html .= "<p style='text-align: center;'><a href='?action=display-shows' style='color: #009688; font-weight: bold;'>Retourner à la liste des spectacles</a></p>";
        $html .= "</div>";
        return $html;
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


