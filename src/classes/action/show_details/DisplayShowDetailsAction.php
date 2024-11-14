<?php

namespace iutnc\nrv\action\show_details;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\render\ShowRenderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage détaillé d’un spectacle : titre, artistes, description, style, durée, image(s),
 * extrait audio/vidéo
 */
class DisplayShowDetailsAction extends Action
{

    public function executePost(): string
    {
        return "";
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function executeGet(): string
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];
        $repository = NrvRepository::getInstance();
        $id = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS);
        $show = $repository->findShowById($id);

        $artists = $repository->findAllArtistsByShow($show->id);
        $show->setListeArtiste($artists);
        return $show->getRender(Renderer::LONG);
    }
}