<?php

namespace iutnc\nrv\render;

use iutnc\nrv\auth\Authz;
use iutnc\nrv\object\Evening;

class EveningRenderer extends DetailsRender
{
    public Evening $evening;

    function __construct(Evening $evening)
    {
        $this->evening = $evening;
    }

    public function renderCompact($index): string
    {
        $extensions = ['jpg', 'gif', 'png'];
        $img = "res/background/evening_default.jpg";

        foreach ($extensions as $ext) {
            $filePath = "res/images/evenings/{$this->evening->id}.$ext";
            if (file_exists($filePath)) {
                $img = $filePath;
                break;
            }
        }

        $imageOverlay = "res/icons/cancel.png";
        $grayscaleStyle = !$this->evening->programmed ? "filter: grayscale(100%);" : "";
        $overlayVisible = !$this->evening->programmed ? "opacity: 1;" : "opacity: 0;";

        return <<<HTML
    <div class="col">
    <div class="card bg-dark text-light hover-effect" style="border-radius: 30px">
        <div class="position-relative" style="height: 0; padding-top: 160%; overflow: hidden; border-radius: 30px;">
            <a href="?action=evening&id={$this->evening->id}" class="text-decoration-none">
                <div class="card-img" style="background-image: url('{$img}'); {$grayscaleStyle}"></div>
          
            
            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; {$overlayVisible}">
                <img src="{$imageOverlay}" alt="Annulé" style="max-width: 90%; max-height: 90%;">
            </div>
            </a>

        </div>
        <a href="?action=showDetails&id={$this->evening->id}" class="text-reset text-decoration-none">
            <div class="card-body text-left" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
                <h5 class="card-title">{$this->evening->title}</h5>
                <p class="card-text">{$this->evening->description}</p>
            </div>
        </a>
    </div>
</div>


HTML;

    }


    public function renderLong($index): string
    {
        $extensions = ['jpg', 'gif', 'png'];
        $img = "res/background/show_default.jpg";

        $cancelbnt = "";
        if (Authz::checkRole(Authz::STAFF)) {
            $cancelbnt =
                <<<HTML
                <form class="btn" action="?action=cancel-evening&id={$this->evening->id}" method="POST">
                        <input type="hidden" name="action" value="cancel-show">
                        <input type="hidden" name="id" value="{$this->evening->id}">
                        <button type="submit" class="btn btn-danger">Annuler</button>
                </form>
                HTML;
        }
        $location = $this->evening->location;

        if ($this->evening->shows == []) {
            $shows = "<p>Aucun spectacle</p>";
        } else {
            $shows = ArrayRenderer::render($this->evening->shows, self::COMPACT, false);
        }

        $renderEvening = <<<HTML
<div class="container evening-container">
    <div class="text-center evening-header">
        <h2>{$this->evening->title}</h2>
        <p class="evening-theme">Thème : {$this->evening->theme}</p>
        $cancelbnt
    </div>
    <div class="row evening-info">
        <div class="col-md-4 info-card">
            <div class="info-content">
                <p class="info-title">Date</p>
                <p>{$this->evening->date}</p>
            </div>
        </div>
        <div class="col-md-4 info-card">
            <div class="info-content">
                <p class="info-title">Lieu</p>
                <p>{$location->address}</p>
            </div>
        </div>
        <div class="col-md-4 info-card">
            <div class="info-content">
                <p class="info-title">Prix</p>
                <p>{$this->evening->eveningPrice} €</p>
            </div>
        </div>
    </div>
    <div class="row evening-description">
        <div class="col-md-12 description-card">
            <p class="info-title">Description</p>
            <p>{$this->evening->description}</p>
        </div>
    </div>
    <div class="row evening-shows">
        <div class="col-md-12">
            <h3 class="shows-title">Spectacles</h3>
            <div class="list-group">
            
                $shows
                
            </div>
        </div>
    </div>
</div>
HTML;

        return $renderEvening;

    }
}