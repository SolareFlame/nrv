<?php

namespace iutnc\nrv\action\show_details;

use iutnc\nrv\action\Action;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;


/**
 * Filtrage de la liste des spectacles par date
 */
class DisplayShowsByDayAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost()
    {
        return "";
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function executeGet()
    {
        $repo = NrvRepository::getInstance();
        $date = filter_var($_GET['date'],FILTER_SANITIZE_SPECIAL_CHARS);
        $shows = $repo->findShowsByDate($date);

        return ArrayRenderer::render($shows,Renderer::COMPACT,true);
    }
}