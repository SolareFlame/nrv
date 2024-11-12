<?php

namespace iutnc\nrv\render;

use iutnc\nrv\action\user_experience\AddShowToFavoritesAction;
use iutnc\nrv\object\Show;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

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
        $id = $this->show->id;
        if (!in_array($id, $_SESSION['favorites'])) {
            $heart = <<<HTML
                    <a href="?action=addShow2Fav&id={$id}"><span id="unfill-heart" style="margin-right: 8px;">♡</span></a>
                    HTML;
        } else {
            $heart = <<<HTML
                    <a href="?action=delShow2fav&id={$id}"><span id="fill-heart" style="margin-right: 8px;">♥</span></a>
                    HTML;
        }

        return <<<HTML
            
            <div style="display: flex; align-items: flex-start;">
                {$heart}
                <div>
                    {$this->show->title} <br>
                    {$this->show->description}
                </div>
            </div>
            HTML;
    }

    public function renderLong($index = null): string
    {
        $heures = (int)$this->show->duration % 59;
        $minutes = $this->show->duration - $heures * 60;
        if ($minutes == 0) {
            $minutes = "00";
        }
        return <<<HTML
                    <div class="show">
                        {$this->show->title} - {$this->show->style}<br>
                        {$this->show->DisplayArtiste()} <br>
                        Le {$this->show->startDate->format('d M Y \à H:i')} pendant {$heures}H{$minutes} <br>
                        {$this->show->description}<br>
                        <a href='index.php?action=evening&showId={$this->show->id}'>Voir le spectacle</a> <br>
                    </div class="show">
                HTML;

    }
}