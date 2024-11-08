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
                    <div class="show">
                        {$this->show->title} - {$this->show->style}<br>
                        {$this->show->DisplayArtiste()} <br>
                        à {$this->show->startDate} pendant {$this->show->duration->format('H:i:s')} <br>
                        <a href='index.php?action=evening&showId={$this->show->id}'>Voir le spectacle</a> <br>
                        {$this->show->url}  {$this->show->description}<br>;
                    </div class="show">
                    <br><br>
                    HTML;

    }
}