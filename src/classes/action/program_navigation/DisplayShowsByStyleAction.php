<?php

namespace iutnc\nrv\action\program_navigation;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Filtrage de la liste des spectacles par style de musique
 */
class DisplayShowsByStyleAction extends Action
{

    public function executePost(): string
    {
        return "";
        // TODO: Implement get() method.
    }

    /**
     * @throws Exception
     */
    public function executeGet(): string
    {
        $repository = NrvRepository::getInstance();
        $style = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $shows = $repository->findShowsByStyle($style);
        return ArrayRenderer::render($shows, Renderer::COMPACT, true);
    }
}