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


    public function renderCompact($index = null): string
    {
        return $this->show->title . " - " . $this->show->description . "<br>" ;
    }

    public function renderLong($index = null): string
    {
        return <<<HTML
                    <div class="show">
                        {$this->show->title} - {$this->show->style}<br>
                        {$this->show->DisplayArtiste()} <br>
                        Le {$this->show->startDate->format('d M Y \à H:i')} pendant {$this->show->duration->format('G\Hi')} <br>
                        {$this->show->description}<br>
                        <a href='index.php?action=evening&showId={$this->show->id}'>Voir le spectacle</a> <br>
                    </div class="show">
                    HTML;

    }
}