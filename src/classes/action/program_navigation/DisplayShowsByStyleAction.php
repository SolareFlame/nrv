<?php

namespace iutnc\nrv\action\program_navigation;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Filtrage de la liste des spectacles par style de musique
 */
class DisplayShowsByStyleAction extends Action
{

    public function executePost()
    {
        // TODO: Implement get() method.
    }

    public function executeGet()
    {
        $repository = NrvRepository::getInstance();
        $style = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS);
        $shows = $repository->findShowsByStyle($style);
        return ArrayRenderer::render($shows,Renderer::COMPACT,true);
    }
}