<?php

namespace iutnc\nrv\render;

use iutnc\nrv\object\Show;
use iutnc\nrv\render\Renderer;

/**
 * Classe PodcastRenderer.
 * Elle permet de représenter un rendu d'un podcast.
 */
class ShowRenderer extends DetailsRender
{
    private Show $show;

    public function __construct(Show $sh)
    {
        $this->show = $sh;
    }


    public function renderCompact(): string
    {
        return $this->show->title . " - " . $this->show->description . "<br>" ;
    }

    public function renderLong(): string
    {
        return <<<HTML
    {$this->show->title} - {$this->show->description}
    {$this->show->DisplayArtiste()} -
    à {$this->show->startDate} pendant {$this->show->duration->format('H:i:s')}
    <a href='index.php?action=evening&showId={$this->show->id}'>Voir le spectacle</a>
    {$this->show->url} {$this->show->style}<br><br><br>
HTML;

    }
}