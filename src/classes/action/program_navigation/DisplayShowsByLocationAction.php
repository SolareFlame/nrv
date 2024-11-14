<?php

namespace iutnc\nrv\action\program_navigation;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Filtrage de la liste des spectacles par lieu
 */
class DisplayShowsByLocationAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executeGet(): string
    {
        $repository = NrvRepository::getInstance();
        $id = filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $shows = $repository->findShowsByLocation($id);
        return ArrayRenderer::render($shows, Renderer::COMPACT, true);
    }
}