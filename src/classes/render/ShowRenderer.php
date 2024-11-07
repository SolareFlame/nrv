<?php

namespace iutnc\nrv\Render;

use iutnc\nrv\object\Show;

/**
 * Classe PodcastRenderer.
 * Elle permet de représenter un rendu d'un podcast.
 */
class ShowRenderer implements Renderer
{
    private Show $show;

    public function __construct(Show $sh)
    {
        $this->show = $sh;
    }

    /**
     * Rendu de la liste audio.
     * @param int $selector , 1 for long, 2 for preview
     * @param bool $isPrivate , vrai si la playlist appartient à un user
     * @param null $index , index de la piste (pour la suppression)
     * @return string le rendu
     */
    public function render(int $selector, $index = null): string
    {
        $res = '';
        switch ($selector) {
            case Renderer::COMPACT:
                break;

            case Renderer::LONG:
                $res .= $this->show->title . " - " . $this->show->description . "<br>" .
                    $this->show->DisplayArtiste() . " - "  .
                    "<br>à " . $this->show->startDate . " pendant " . $this->show->duration->format('H:i:s') . "<br>" .
                    "<a href='index.php?action=evening&showId=" . $this->show->id . "'>Voir le spectacle</a><br>" .
                    " $this->show->url $this->show->style<br><br><br> ";
                return $res;

            default:
                return "g pas Kanpri";
        }
    }

}