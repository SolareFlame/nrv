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
            ? "<a href='?action=addShow2Fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_void.png' alt='not liked'></a>"
            : "<a href='?action=delShow2fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_full.png' alt='liked'></a>";

        return <<<HTML
<div class="col">
    <div class="card bg-dark text-light hover-effect" style="border-radius: 30px">
        <div class="position-relative" style="height: 0; padding-top: 100%; overflow: hidden; border-radius: 30px;">
            <a href="?action=showDetails&id={$this->show->id}" class="text-decoration-none">
                <div class="card-img" style="background-image: url('res/background/show_default.jpg');"></div>
            </a>
            <div class="position-absolute top-0 end-0 p-2" style="z-index: 2;">
                {$heart}
            </div>
        </div>
        <a href="?action=showDetails&id={$this->show->id}" class="text-reset text-decoration-none">
            <div class="card-body text-center" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
                <h5 class="card-title">{$this->show->title}</h5>
                <p class="card-text">{$this->show->description}</p>
            </div>
        </a>
    </div>
</div>

HTML;
    }

    public function renderLong($index = null): string
    {
        if ($this->show->duration < 59) {
            $heures = 0;
            $minutes = $this->show->duration;
        } else {
            $heures = (int)$this->show->duration % 59;
            $minutes = $this->show->duration - $heures * 60;
        }

        if ($minutes == 0) {
            $minutes = "00";
        }

        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }

        $id = $this->show->id;

        $heart = !in_array($id, $_SESSION['favorites'])
            ? "<a href='?action=addShow2Fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_void.png' alt='not liked'></a>"
            : "<a href='?action=delShow2fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_full.png' alt='liked'></a>";


        return <<<HTML
<div class="col">
    <div class="card bg-dark text-light hover-effect" style="border-radius: 30px">
        <div class="position-relative" style="height: 0; padding-top: 120%; overflow: hidden; border-radius: 30px;">
            <a href="?action=showDetails&id={$this->show->id}" class="text-decoration-none">
                <div class="card-img" style="background-image: url('res/background/show_default.jpg');"></div>
            </a>
            <div class="position-absolute top-0 end-0 p-2" style="z-index: 2;">
                {$heart}
            </div>
        </div>
        <a href="?action=showDetails&id={$this->show->id}" class="text-reset text-decoration-none">
            <div class="card-body text-center" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
                <h3 class="show-title">{$this->show->title} - {$this->show->style}</h3>
                <p class="show-artist">{$this->show->DisplayArtiste()}</p>
                <p class="show-details">Le {$this->show->startDate->format('d M Y \à H:i')} pendant {$heures}H{$minutes}</p>
                <p class="show-description">{$this->show->description}</p>
                <a href="index.php?action=evening&id={$this->show->id}" class="show-link">Voir la soirée</a> <br> <br>
                
                <iframe width="560" height="315" src="{$this->show->url}" 
                title="YouTube video player" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                
            </div>
        </a>
    </div>
</div>
HTML;

    }

}