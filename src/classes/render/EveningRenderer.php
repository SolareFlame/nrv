<?php

namespace iutnc\nrv\render;

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
        return <<<HTML
    <div class="col">
    <div class="card bg-dark text-light hover-effect" style="border-radius: 30px">
        <div class="position-relative" style="height: 0; padding-top: 160%; overflow: hidden; border-radius: 30px;">
            <a href="?action=evening&id={$this->evening->id}" class="text-decoration-none">
                <div class="card-img" style="background-image: url('res/background/evening_default.jpg');"></div>
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
        $location = $this->evening->location;
        $renderEvening = <<<HTML
<div class="container evening-container">
    <div class="text-center evening-header">
        <h2>{$this->evening->title}</h2>
        <p class="evening-theme">Thème : {$this->evening->theme}</p>
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
HTML;

        $shows = ArrayRenderer::render($this->evening->shows, self::COMPACT, false);

        $renderEvening .= $shows . <<<HTML
            </div>
        </div>
    </div>
</div>
HTML;

        return $renderEvening;

    }
}