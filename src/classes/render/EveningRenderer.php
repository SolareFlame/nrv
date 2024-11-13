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
    <a href="?action=evening&id={$this->evening->id}" class="text-reset text-decoration-none">
        <div class="card bg-dark text-light hover-effect">
            <div class="position-relative">
                <img src="res/background/background_2.jpg" class="card-img-top" alt="Nom Spectacle">
            </div>
            <div class="card-body text-center">
                <h5 class="card-title">{$this->evening->title}</h5>
                <p class="card-text">{$this->evening->description}</p>
            </div>
        </div>
    </a>
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

        $shows = "";
        foreach ($this->evening->shows as $show) {
            $renderShow = new ShowRenderer($show);
            $shows .= $renderShow->render(Renderer::LONG) ;
        }

        $renderEvening .= $shows . <<<HTML
            </div>
        </div>
    </div>
</div>
HTML;

        return $renderEvening;

    }
}