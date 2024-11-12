<?php

namespace iutnc\nrv\action\show_details;
use iutnc\nrv\action\Action;

/**
 * Affichage détaillé d’un spectacle : titre, artistes, description, style, durée, image(s),
 * extrait audio/vidéo
 */
class DisplayShowDetailsAction extends Action
{

    public function executePost()
    {

        // TODO: Implement get() method.
    }

    public function executeGet()
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];
        // TODO: Implement post() method.
    }
}