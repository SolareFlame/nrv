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
                /*$ret = "<br> {$this->podcast->titre}";
                $ret .= ($this->podcast->auteur === AudioList::NO_AUTEUR) ? "" : " - {$this->podcast->auteur}";  // s'il n'y a pas d'auteur on affiche rien sinon on affiche l'auteur
                $ret .= " - " . sprintf("%02d:%02d", $minutes, $seconds) . "<br> <br> 
                        <audio id='audioPlayer' controls src='{$this->podcast->nom_fich}'> </audio> <br>";
                return $ret;*/

            case Renderer::LONG:
                $res .= $this->show->title . " - " . $this->show->DisplayArtiste() . " - " . $this->show->description .
                    "<br>à " . $this->show->startDate . " pendant " . $this->show->duration . "<br>" .
                    "<a href='index.php?action=evening&showId=" . $this->show->id . "'>Voir le spectacle</a><br>" .
                    " $this->show->url $this->show->style<br><br><br> ";
                return $res;

            default:
                return "g pas Kanpri";
        }
    }

}