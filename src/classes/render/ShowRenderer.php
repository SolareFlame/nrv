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

        // Check if the show is in the user's favorites
        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }
        $heart = !in_array($id, $_SESSION['favorites'])
            ? "<a href='?action=addShow2Fav&id={$id}' class='favorite-icon'>♡</a>"
            : "<a href='?action=delShow2fav&id={$id}' class='favorite-icon'>♥</a>";

        return <<<HTML
        <div class="col">
            <div class="card bg-dark text-light">
                <div class="position-relative">
                    <img src="../../../res/background/background_2.jpg" class="card-img-top" alt="Nom Spectacle">
                    <div class="position-absolute top-0 end-0 p-2">
                        {$heart}
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title">{$this->show->title}</h5>
                    <p class="card-text">{$this->show->description}</p>
                </div>
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